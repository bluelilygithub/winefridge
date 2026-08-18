#!/usr/bin/env python3
"""Scrape racking / cellar images from vintagecellars.com into the theme."""
from __future__ import annotations

import re
import ssl
import urllib.request
from pathlib import Path
from urllib.parse import urljoin, urlparse

BASE = "https://vintagecellars.com"
PAGES = [
    "wine-racks",
    "wine-cellar-design",
    "wine-cabinets.html",
    "wine-racks/vintageview-metal-wine-racks.html",
    "store",
]
OUT = Path("/Users/michaelbarrrett/Local Sites/winefridge/app/public/wp-content/themes/curam-wines/assets/images/racking")
OUT.mkdir(parents=True, exist_ok=True)

SKIP = ("favicon", "icon", "logo", "sprite", "placeholder", "1x1", "pixel", "opensans", "font", "badge")
CTX = ssl.create_default_context()

UA = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36"


def fetch(url: str) -> bytes:
    req = urllib.request.Request(url, headers={"User-Agent": UA})
    with urllib.request.urlopen(req, context=CTX, timeout=45) as r:
        return r.read()


def collect_urls(html: str) -> set[str]:
    found: set[str] = set()
    for m in re.finditer(
        r'(?:src|data-src|data-original)=["\']([^"\']+\.(?:jpg|jpeg|png|webp))["\']',
        html,
        re.I,
    ):
        found.add(urljoin(BASE, m.group(1)))
    for m in re.finditer(r'(/media/[^"\'\s,]+\.(?:jpg|jpeg|png|webp))', html, re.I):
        found.add(urljoin(BASE, m.group(1)))
    # Prefer largest responsive variants when srcset lists scales
    for m in re.finditer(r'srcset=["\']([^"\']+)["\']', html, re.I):
        parts = [p.strip().split()[0] for p in m.group(1).split(",") if p.strip()]
        for p in parts:
            if re.search(r"\.(?:jpg|jpeg|png|webp)(?:$|\?)", p, re.I):
                found.add(urljoin(BASE, p))
    return found


def useful(url: str) -> bool:
    low = url.lower()
    return not any(s in low for s in SKIP)


def pick_largest(urls: list[str]) -> list[str]:
    """Collapse responsive variants to the largest width when filenames share a stem."""
    groups: dict[str, list[tuple[int, str]]] = {}
    plain: list[str] = []
    for u in urls:
        m = re.search(r"(.*?)(?:_c_scale,w_(\d+)|_w_(\d+)|-(\d+)x(\d+))(\.(?:jpg|jpeg|png|webp))", u, re.I)
        if m:
            stem = m.group(1) + m.group(6)
            w = int(m.group(2) or m.group(3) or m.group(4) or 0)
            groups.setdefault(stem, []).append((w, u))
        else:
            plain.append(u)
    out = list(plain)
    for items in groups.values():
        items.sort(key=lambda t: t[0])
        out.append(items[-1][1])
    return sorted(set(out))


def main() -> None:
    imgs: set[str] = set()
    for path in PAGES:
        url = urljoin(BASE + "/", path)
        print(f"FETCH {url}")
        try:
            html = fetch(url).decode("utf-8", "ignore")
        except Exception as e:
            print(f"  FAIL {e}")
            continue
        page_imgs = {u for u in collect_urls(html) if useful(u)}
        print(f"  {len(page_imgs)} images")
        imgs |= page_imgs

    candidates = pick_largest(sorted(imgs))
    print(f"\nCandidates: {len(candidates)}")
    for u in candidates:
        print(u)

    # Prefer cellar / rack / wine photography over generic banners
    scored: list[tuple[int, str]] = []
    for u in candidates:
        low = u.lower()
        score = 0
        for kw, pts in (
            ("rack", 5),
            ("cellar", 5),
            ("wine", 3),
            ("cabinet", 3),
            ("wood", 2),
            ("display", 2),
            ("diamond", 2),
            ("magnum", 2),
            ("gallery", 2),
            ("project", 2),
            ("homepage", -2),
            ("banner", -3),
            ("hero", -1),
        ):
            if kw in low:
                score += pts
        scored.append((score, u))
    scored.sort(key=lambda t: (-t[0], t[1]))

    # Download top unique images for styles + process illustration
    names = [
        "high-density",
        "display",
        "mixed",
        "diamond",
        "magnum",
        "custom",
        "process-illustration",
    ]
    used: set[str] = set()
    mapping: dict[str, str] = {}
    for name in names:
        for score, url in scored:
            if url in used:
                continue
            if score < 1 and name != "process-illustration":
                continue
            ext = Path(urlparse(url).path).suffix.lower() or ".jpg"
            if ext not in {".jpg", ".jpeg", ".png", ".webp"}:
                ext = ".jpg"
            dest = OUT / f"{name}{ext}"
            try:
                print(f"DOWNLOAD {name} <- {url}")
                data = fetch(url)
                if len(data) < 8000:
                    print(f"  skip tiny ({len(data)} bytes)")
                    continue
                dest.write_bytes(data)
                used.add(url)
                mapping[name] = dest.name
                print(f"  saved {dest} ({len(data)} bytes)")
                break
            except Exception as e:
                print(f"  FAIL {e}")
        else:
            print(f"NO IMAGE for {name}")

    print("\nMAPPING")
    for k, v in mapping.items():
        print(f"{k}={v}")


if __name__ == "__main__":
    main()
