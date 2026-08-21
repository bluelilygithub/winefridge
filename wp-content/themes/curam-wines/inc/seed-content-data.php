<?php
/**
 * Starter FAQ copy from the original FAQ page + homepage strip.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function cw_get_seed_faq_items() {
	return [
		[
			'title'   => 'Do I need a builder or renovation?',
			'content' => 'No. Every unit arrives as a finished, self-contained cabinet. Our team positions it, connects power, and commissions the climate controls.',
			'cats'    => [ 'products', 'home' ],
		],
		[
			'title'   => 'What does installed price include?',
			'content' => 'The unit, delivery, and installation by our own team. Metro areas are covered as standard — we confirm regional costs upfront before you commit.',
			'cats'    => [ 'installation', 'home' ],
		],
		[
			'title'   => 'How long from order to working cabinet?',
			'content' => 'Usually six to nine weeks door to door. Build is four to eight weeks; metro install is typically booked within three weeks of completion.',
			'cats'    => [ 'installation', 'home' ],
		],
		[
			'title'   => 'Will it fit my apartment / garage / balcony?',
			'content' => 'Tell us your bottle count and room dimensions — we confirm the right configuration before anything is manufactured. Flat-packed delivery is available for tight access.',
			'cats'    => [ 'sizing', 'home' ],
		],
		[
			'title'   => 'Where do you deliver?',
			'content' => 'Nationwide. Sydney, Melbourne, Brisbane, Perth, Adelaide, and Canberra are standard. Regional freight and install are quoted upfront.',
			'cats'    => [ 'installation', 'home' ],
		],
		[
			'title'   => 'Are your units genuinely self-contained, or do they require a builder?',
			'content' => "<p>Genuinely self-contained. Every unit we supply is manufactured as a complete, assembled product — insulated panels, conditioning system, glazing, racking and wiring are all integrated before it leaves our facility. Installation means positioning the unit, connecting it to a standard power point, and commissioning the climate controls. No building work, no permits, no trades on site beyond our own installation team in most cases.</p>",
			'cats'    => [ 'products' ],
		],
		[
			'title'   => "What's the difference between your three series?",
			'content' => "<p>The climate system is identical across all three — ±0.5°C temperature precision, active humidity management. The difference is the enclosure:</p><p><strong>Panoramic Glass Series</strong> — freestanding or niche-fit glass pods for living rooms, dining rooms, and apartments where the unit is part of the interior design.</p><p><strong>Insulated Panel Series</strong> — matte-finish insulated panel units for garages, alcoves, and utility spaces. Maximum capacity with a functional, workhorse aesthetic.</p><p><strong>Weather-Resistant Series</strong> — fully enclosed outdoor-rated units for covered balconies and semi-exposed areas. Connects to a standard exterior power point.</p>",
			'cats'    => [ 'products' ],
		],
		[
			'title'   => "What's the difference between a wine cabinet, a cigar humidor cabinet, and an art storage cabinet?",
			'content' => "<p>Structurally, they're similar — an insulated, climate-controlled enclosure with a sealed glazed door and a dedicated conditioning unit. The difference is in the target climate and interior materials.</p><p>Wine needs stable cool temperatures (12–14°C) and moderate humidity. Cigars need closer to room temperature but much higher, tighter humidity control (68–72% RH) and cedar-lined interiors. Art needs low, very stable humidity, UV-filtered glass and lighting, and archival materials that don't off-gas.</p><p>The shell can look almost identical; the internals are built differently for each use case.</p>",
			'cats'    => [ 'products' ],
		],
		[
			'title'   => 'Can I convert a wine cabinet into a cigar humidor later, or vice versa?',
			'content' => "<p>Not easily. The conditioning systems are tuned differently — a wine cellar's compressor is set up to cool and dehumidify together, which will run a cigar space too dry. Converting between uses generally means replacing the humidification system and relining the interior, not just adjusting a thermostat. If you're considering both uses, it's worth discussing a purpose-built unit for each rather than trying to convert between them later.</p>",
			'cats'    => [ 'products' ],
		],
		[
			'title'   => 'Can the unit be relocated if I move house or redesign the space?',
			'content' => "<p>Yes — this is one of the practical advantages of a self-contained unit over a built-in-place cellar. The unit can be disconnected, moved, and recommissioned in a new location. Freestanding glass units in particular are designed with this in mind. Panel units that have been installed into an alcove may require more disassembly. We can advise on relocation logistics specific to your unit.</p>",
			'cats'    => [ 'products' ],
		],
		[
			'title'   => 'Is the stated bottle capacity accurate?',
			'content' => "<p>It's accurate for a uniform reference load — usually a standard 750ml bottle racked wall to wall. Real-world capacity is typically lower once mixed bottle shapes (magnums, Champagne, large-format), drawers, or a decanting surface are factored in. Ask what the capacity looks like for your actual intended contents rather than relying on the headline figure alone.</p>",
			'cats'    => [ 'sizing' ],
		],
		[
			'title'   => 'Can the size be customised?',
			'content' => "<p>Yes — external dimensions can be adjusted to suit available space, since most installations are driven by what will fit rather than a fixed standard size. Customisation may affect lead time and price. Confirm the dimensional requirements early so they can be locked in before manufacturing begins.</p>",
			'cats'    => [ 'sizing' ],
		],
		[
			'title'   => 'Can multiple units be placed side by side to increase total capacity?',
			'content' => "<p>Yes. Panel series units in particular are designed with modular expansion in mind — units can be positioned adjacent to each other and joined, sharing a common wall to improve thermal efficiency. Each unit runs its own conditioning system. We can advise on the most efficient configuration for your target capacity.</p>",
			'cats'    => [ 'sizing' ],
		],
		[
			'title'   => 'What if my doorway or stairwell is too narrow for delivery?',
			'content' => "<p>Most walk-in units are supplied flat-packed and assembled on site for exactly this reason. The individual panel components have their own size limits, so access is still worth checking before confirming an order — particularly for apartments with narrow lift lobbies or tight stair access. Site inspection before manufacture is the best way to catch this early.</p>",
			'cats'    => [ 'sizing' ],
		],
		[
			'title'   => 'Do I need a site inspection before ordering?',
			'content' => "<p>For built-in-place or larger units, yes, in most cases. A site inspection confirms door widths, stair and lift access, ceiling clearance, floor levelness, and power point location before the unit is manufactured. Skipping this step is the most common cause of on-site complications and additional costs. For smaller freestanding units going into standard residential spaces, it's often not required.</p>",
			'cats'    => [ 'sizing' ],
		],
		[
			'title'   => 'What temperature and humidity should a wine cabinet run at?',
			'content' => "<p>Typically 12–14°C and 60–70% relative humidity. Stability matters more than hitting an exact number — temperature and humidity fluctuation is more damaging to wine over time than a slightly off set point held consistently. Our units target ±0.5°C precision as standard.</p>",
			'cats'    => [ 'climate' ],
		],
		[
			'title'   => 'Why does humidity matter as much as temperature?',
			'content' => "<p>Cork is a natural material. In a dry environment (below around 55% RH), cork desiccates slowly, loses elasticity, and the seal it forms against the bottle neck becomes imperfect — air enters, the wine oxidises. At the other extreme, excess humidity encourages mould on labels and, in timber racking, can cause structural issues. The 60–70% RH range keeps cork in good condition and labels intact over long-term storage.</p>",
			'cats'    => [ 'climate' ],
		],
		[
			'title'   => "Why can't a standard wine fridge maintain proper cigar humidity?",
			'content' => "<p>Because cooling and dehumidifying are the same process in most compressor refrigeration systems. Wine wants cool temperatures and moderately controlled humidity. Cigars want closer to room temperature but much higher humidity (68–72% RH). A cigar-specific unit needs a dedicated humidification system working independently of the cooling cycle — the two requirements are fundamentally in tension in a standard wine cooling unit.</p>",
			'cats'    => [ 'climate' ],
		],
		[
			'title'   => 'Why does art storage need such stable humidity?',
			'content' => "<p>Paper, canvas, and wood substrates expand and contract with humidity swings. Repeated cycling causes cracking, warping, and paint separation over time. The exact humidity target matters less than eliminating fluctuation — a stable 50% RH is better for most works than an average of 55% that swings between 40% and 70%.</p>",
			'cats'    => [ 'climate' ],
		],
		[
			'title'   => "Is UV protection necessary, or is that just upselling?",
			'content' => "<p>For wine and art, no — it's a genuine requirement. UV exposure degrades wine over months and fades or damages pigments and substrates in artwork much faster than most people expect. This applies to both the glazing and the interior lighting, and the two need to be checked separately. UV-filtered glass doesn't help if the interior spotlights are emitting UV.</p>",
			'cats'    => [ 'climate' ],
		],
		[
			'title'   => 'What type of cooling system should I look for?',
			'content' => "<p>Compressor-based refrigeration — either a self-contained unit or a split system with an external condenser. Avoid thermoelectric (Peltier) cooling for anything larger than a small countertop unit; Peltier systems don't have the capacity to hold a stable climate in a room-sized or large cabinet space, and they struggle to maintain set points through Australian summer ambient temperatures.</p>",
			'cats'    => [ 'climate' ],
		],
		[
			'title'   => "What happens if the unit fails while I'm away?",
			'content' => "<p>This is exactly what a monitoring and alarm system is for. At minimum, look for a temperature and humidity excursion alarm — audible at the unit. App-based remote monitoring is a meaningful upgrade if offered, since a failure caught within hours is a repair; a failure caught after two weeks can be a total loss of contents. Ask specifically about alarm and monitoring options when specifying.</p>",
			'cats'    => [ 'reliability' ],
		],
		[
			'title'   => "How long should the conditioning unit last, and what's covered under warranty?",
			'content' => "<p>Ask specifically about parts versus labour warranty terms — they're often different lengths, and what looks like a comprehensive warranty may cover parts only, with labour charged separately. Also confirm whether the compressor is a standard type serviceable by a local technician or refrigeration engineer, since some imported units become difficult or expensive to repair once out of warranty.</p>",
			'cats'    => [ 'reliability' ],
		],
		[
			'title'   => 'Will running the unit be expensive?',
			'content' => "<p>Running cost is driven mainly by insulation quality and door sealing, not just the efficiency rating of the conditioning unit itself. A well-insulated, well-sealed cabinet lets the compressor cycle less frequently — that's where the real savings are. A unit with a higher-efficiency compressor but poor insulation will cost more to run than a unit with a standard compressor and excellent insulation. Ask to see the insulation specification, not just the energy rating.</p>",
			'cats'    => [ 'reliability' ],
		],
		[
			'title'   => 'What power supply do the units require?',
			'content' => "<p>Most units in our range connect to a standard 10A power point — the same outlet used by a domestic refrigerator or washing machine. Larger units may require a 15A outlet, which is common in garages and commercial premises but may need to be installed by an electrician if not already present. We confirm the power requirement during the specification process so there are no surprises on the day of installation.</p>",
			'cats'    => [ 'installation' ],
		],
		[
			'title'   => 'Is installation included in the price?',
			'content' => "<p>Installation is included for metro areas as standard. Regional and remote deliveries may incur additional freight and labour charges — confirm this when requesting a quote, particularly if you're outside a major metro area. We'll tell you exactly what's included before you commit.</p>",
			'cats'    => [ 'installation' ],
		],
		[
			'title'   => 'How long does installation take?',
			'content' => "<p>A freestanding glass unit can typically be positioned, levelled, and connected within a few hours. Larger flat-packed units assembled on site generally take one to two days depending on size, configuration, and any site-specific access considerations. We'll give you a time estimate specific to your unit before the installation date.</p>",
			'cats'    => [ 'installation' ],
		],
		[
			'title'   => 'What ongoing maintenance does the unit need?',
			'content' => "<p>Compressor-based refrigeration systems benefit from periodic servicing — cleaning condenser coils, checking door seals, verifying refrigerant levels — similar to any commercial refrigeration equipment. Ask what the recommended service interval is and whether servicing is available from a local refrigeration technician or requires a specialist. Keeping a log of temperature and humidity readings is also worthwhile for high-value collections.</p>",
			'cats'    => [ 'installation' ],
		],
		[
			'title'   => "What if the unit doesn't suit my space after delivery?",
			'content' => "<p>We conduct a site inspection before manufacturing for most built-in or larger units specifically to avoid this. For standard freestanding units, we provide dimensional specifications in advance and ask you to verify the space. If a unit is genuinely unsuitable due to an error on our part, we'll work to resolve it. This is why the pre-order specification conversation matters — it protects both parties.</p>",
			'cats'    => [ 'installation' ],
		],
		[
			'title'   => 'Do your units suit restaurant and bar applications?',
			'content' => "<p>Yes. We supply to restaurants, bars, hotels, and private member clubs. For commercial venues, the key considerations are front-of-house visibility (glass series units are designed for this), service access during trade (door swing, aisle clearance), and capacity relative to the program. We've designed units specifically around the dual role of working cellar and guest-facing display — both requirements are non-negotiable in a hospitality context, and the spec reflects that.</p>",
			'cats'    => [ 'commercial' ],
		],
		[
			'title'   => 'Can a unit serve as front-of-house display as well as functional storage?',
			'content' => "<p>Yes — this is one of the primary use cases for the Panoramic Glass Series. Full-height glass panels, internal LED lighting, and the option for backlit display racking mean the unit functions as a working cellar with correct climate while being visible and impressive from the dining room or bar. The glass acts as both a thermal boundary and a design element.</p>",
			'cats'    => [ 'commercial' ],
		],
		[
			'title'   => 'What bottle count is practical for a commercial venue?',
			'content' => "<p>This varies significantly by venue type and wine program depth. A boutique restaurant with a focused list might run comfortably from a 400–600 bottle unit. A hotel restaurant or venue with a serious wine program typically needs 800–1,500 bottles of working stock accessible on site, with larger reserve storage separately. We scope the brief around your actual program — current list depth, turnover rate, and whether bulk storage or display is the priority.</p>",
			'cats'    => [ 'commercial' ],
		],
		[
			'title'   => 'Can you supply multiple units for a large venue or project?',
			'content' => "<p>Yes. For larger projects — hotel fit-outs, multi-venue groups, large residential developments — we work from a consolidated brief and can coordinate manufacture and installation scheduling across units. Contact us directly to discuss project scope and timeline.</p>",
			'cats'    => [ 'commercial' ],
		],
	];
}
