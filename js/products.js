// Alloyé — Product data
// To add a real product later: copy one object below, change the fields,
// and drop the matching photo into /images/products/

const PRODUCTS = [
  {
    id: 1,
    name: "Lotus Kemp Necklace",
    category: "necklaces",
    material: "Stainless Steel, Kemp Stone",
    price: 1850,
    oldPrice: null,
    image: "images/products/necklace-lotus-kemp-set.jpg",
    badge: "New",
    featured: true,
    description:
      "A traditional lotus-motif necklace hand-set with kemp stones along a beaded chain — shown with matching earrings, sold separately.",
    care: "Waterproof and tarnish-resistant. Avoid direct perfume contact with the stones. Store flat in the provided box.",
    dimensions: "Chain length: 16 inches."
  },
  {
    id: 2,
    name: "Élan Ring",
    category: "rings",
    material: "Stainless Steel",
    price: 950,
    oldPrice: null,
    image: "images/products/placeholder-ring-1.jpg",
    badge: "Best Seller",
    featured: true,
    description:
      "A minimal statement ring with a soft geometric silhouette — pairs effortlessly with stacked rings or worn alone.",
    care: "Waterproof and tarnish-resistant. Polish gently with a soft cloth to keep the finish bright.",
    dimensions: "Available in sizes 6–9."
  },
  {
    id: 3,
    name: "Shell Charm Bracelet",
    category: "bracelets",
    material: "Stainless Steel",
    price: 250,
    oldPrice: null,
    image: "images/products/bracelet-shell-charm-jade.jpg",
    badge: "New",
    featured: true,
    description:
      "A double-chain bracelet layered with jade beads and finished with a gold shell charm — a relaxed, beach-inspired everyday piece.",
    care: "Waterproof and tarnish-resistant. Wipe with a soft, dry cloth to maintain shine.",
    dimensions: "Length: 7 inches with 1 inch extender."
  },
  {
    id: 4,
    name: "Puffed Heart Stud Earrings",
    category: "earrings",
    material: "Stainless Steel",
    price: 950,
    oldPrice: null,
    image: "images/products/earring-puffed-heart.jpg",
    badge: "New",
    featured: true,
    description:
      "Chunky puffed heart studs with a high-polish finish — a playful, statement-making everyday earring.",
    care: "Waterproof and tarnish-resistant. Wipe clean with a soft cloth.",
    dimensions: "Approx. 1.6 cm."
  },
  {
    id: 5,
    name: "Celeste Pearl Set",
    category: "sets",
    material: "Stainless Steel, Pearl",
    price: 2200,
    oldPrice: null,
    image: "images/products/placeholder-set-1.jpg",
    badge: "New",
    featured: true,
    description:
      "A matching necklace and earring set finished with hand-set pearls, designed for special occasions.",
    care: "Stainless steel base is waterproof and tarnish-resistant. Store flat in the provided box.",
    dimensions: "Necklace: 16 inch chain. Earrings: 1.8 cm drop."
  },
  {
    id: 6,
    name: "Oval Link Necklace Set",
    category: "sets",
    material: "Stainless Steel",
    price: 1550,
    oldPrice: null,
    image: "images/products/necklace-oval-link.jpg",
    badge: "New",
    featured: false,
    description:
      "A matching set with a polished oval-link necklace, bracelet, and stud earrings — a complete, coordinated look.",
    care: "Waterproof and tarnish-resistant — safe for daily wear.",
    dimensions: "Necklace: 18 inch chain. Bracelet: 7 inches. Earrings: 1.5 cm."
  },
  {
    id: 7,
    name: "Spiral Swirl Hoop Earrings",
    category: "earrings",
    material: "Stainless Steel",
    price: 1150,
    oldPrice: null,
    image: "images/products/earring-spiral-swirl-hoop.jpg",
    badge: "New",
    featured: false,
    description:
      "Sculptural teardrop hoops finished with a spiral centre — a bold, modern everyday hoop.",
    care: "Waterproof and tarnish-resistant. Wipe clean with a soft cloth.",
    dimensions: "Approx. 3 cm drop."
  },
  {
    id: 8,
    name: "Golden Orchid Statement Earrings",
    category: "earrings",
    material: "Stainless Steel",
    price: 1450,
    oldPrice: null,
    image: "images/products/earring-golden-orchid.jpg",
    badge: "New In",
    featured: false,
    description:
      "Orchid-inspired statement studs with hand-textured petals — a striking piece for evening wear.",
    care: "Waterproof and tarnish-resistant. Wipe clean with a soft cloth.",
    dimensions: "Drop length: 4 cm."
  },
  {
    id: 9,
    name: "Mini Stud Trio Set",
    category: "earrings",
    material: "Stainless Steel, Cubic Zirconia",
    price: 850,
    oldPrice: null,
    image: "images/products/earring-mini-stud-trio.jpg",
    badge: "Best Seller",
    featured: false,
    description:
      "Three delicate stud pairs in one set — a round crystal stud, a flower crystal stud, and a pavé clover stud — mix and match or wear all three.",
    care: "Hypoallergenic stainless steel posts. Polish gently with a soft cloth.",
    dimensions: "Sold as a set of 3 pairs."
  },
  {
    id: 10,
    name: "Solene Band",
    category: "rings",
    material: "Stainless Steel",
    price: 850,
    oldPrice: null,
    image: "images/products/placeholder-ring-2.jpg",
    badge: "",
    featured: false,
    description:
      "A thin, understated band designed for stacking or wearing alone.",
    care: "Waterproof and tarnish-resistant — safe for washing hands and light exercise.",
    dimensions: "Available in sizes 5–9."
  },
  {
    id: 11,
    name: "Pearl Shell Charm Bracelet",
    category: "bracelets",
    material: "Stainless Steel, Freshwater Pearl",
    price: 250,
    oldPrice: null,
    image: "images/products/bracelet-shell-pearl-charm.jpg",
    badge: "New",
    featured: false,
    description:
      "A chain bracelet strung with multicolor beads, finished with a gold shell charm and a single freshwater pearl.",
    care: "Waterproof and tarnish-resistant. Wipe with a soft, dry cloth.",
    dimensions: "Length: 6.5 inches with 1 inch extender."
  },
  {
    id: 12,
    name: "Amara Bridal Set",
    category: "sets",
    material: "Stainless Steel, Cubic Zirconia",
    price: 2600,
    oldPrice: 2900,
    image: "images/products/placeholder-set-2.jpg",
    badge: "Limited Edition",
    featured: false,
    description:
      "A statement necklace and earring set designed for brides and special occasions, finished with hand-set stones.",
    care: "Stainless steel base is waterproof and tarnish-resistant. Store flat in the provided box.",
    dimensions: "Necklace: 15 inch chain. Earrings: 2.8 cm drop."
  },
  {
    id: 13,
    name: "Floral Kemp Necklace",
    category: "necklaces",
    material: "Stainless Steel, Kemp Stone",
    price: 1950,
    oldPrice: null,
    image: "images/products/necklace-floral-kemp-set.jpg",
    badge: "New",
    featured: false,
    description:
      "A hand-set floral kemp necklace with a five-petal motif along the chain — shown with matching earrings, sold separately.",
    care: "Waterproof and tarnish-resistant. Avoid direct perfume contact with the stones. Store flat in the provided box.",
    dimensions: "Chain length: 16 inches."
  },
  {
    id: 14,
    name: "Seashell Charm Necklace",
    category: "necklaces",
    material: "Stainless Steel",
    price: 1250,
    oldPrice: null,
    image: "images/products/necklace-seashell-charm.jpg",
    badge: "New",
    featured: true,
    description:
      "A chain necklace layered with seashell, pearl, and seahorse charms — an easy everyday piece with a coastal feel.",
    care: "Waterproof and tarnish-resistant — safe for daily wear, showering, and swimming.",
    dimensions: "Chain length: 17 inches."
  },
  {
    id: 15,
    name: "Spiral Pendant Necklace",
    category: "necklaces",
    material: "Stainless Steel",
    price: 1050,
    oldPrice: null,
    image: "images/products/necklace-spiral-pendant.jpg",
    badge: "",
    featured: false,
    description:
      "A sculptural spiral pendant on a fine snake chain — a bold, minimal centerpiece for everyday wear.",
    care: "Waterproof and tarnish-resistant — safe for daily wear.",
    dimensions: "Chain length: 18 inches. Pendant: 2.8 cm."
  },
  {
    id: 16,
    name: "Horseshoe Pendant Necklace",
    category: "necklaces",
    material: "Stainless Steel",
    price: 950,
    oldPrice: null,
    image: "images/products/necklace-horseshoe-pendant.jpg",
    badge: "",
    featured: false,
    description:
      "A smooth, sculptural horseshoe pendant on a fine snake chain, designed as a simple everyday layering piece.",
    care: "Waterproof and tarnish-resistant — safe for daily wear.",
    dimensions: "Chain length: 18 inches. Pendant: 2 cm."
  },
  {
    id: 18,
    name: "Layered Chain Necklace Set",
    category: "necklaces",
    material: "Stainless Steel, Cubic Zirconia",
    price: 2100,
    oldPrice: null,
    image: "images/products/necklace-layered-set.jpg",
    badge: "New",
    featured: false,
    description:
      "A set of five fine chain necklaces, each finished with a different clover, gem, or charm pendant — wear together for a layered look or separately.",
    care: "Waterproof and tarnish-resistant — safe for daily wear.",
    dimensions: "Chain lengths: 16-20 inches, sold as a set of 5."
  },
  {
    id: 20,
    name: "Hammered Dome Hoop Earrings",
    category: "earrings",
    material: "Stainless Steel",
    price: 1250,
    oldPrice: null,
    image: "images/products/earring-hammered-dome-hoop.jpg",
    badge: "",
    featured: false,
    description:
      "Chunky domed hoops with a hand-hammered texture — a bold, tactile everyday earring.",
    care: "Waterproof and tarnish-resistant. Wipe clean with a soft cloth.",
    dimensions: "Diameter: 2.8 cm."
  },
  {
    id: 21,
    name: "Butterfly Stud Trio Set — Gold",
    category: "earrings",
    material: "Stainless Steel, Cubic Zirconia",
    price: 950,
    oldPrice: null,
    image: "images/products/earring-butterfly-trio-gold.jpg",
    badge: "New",
    featured: false,
    description:
      "Three stud pairs in one set — a round crystal stud, a ball stud, and a pavé butterfly stud — in gold tone.",
    care: "Hypoallergenic stainless steel posts. Polish gently with a soft cloth.",
    dimensions: "Sold as a set of 3 pairs."
  },
  {
    id: 22,
    name: "Butterfly Stud Trio Set — Silver",
    category: "earrings",
    material: "Stainless Steel, Cubic Zirconia",
    price: 950,
    oldPrice: null,
    image: "images/products/earring-butterfly-trio-silver.jpg",
    badge: "New",
    featured: false,
    description:
      "Three stud pairs in one set — a round crystal stud, a ball stud, and a pavé butterfly stud — in silver tone.",
    care: "Hypoallergenic stainless steel posts. Polish gently with a soft cloth.",
    dimensions: "Sold as a set of 3 pairs."
  },
  {
    id: 23,
    name: "Infinity Knot Stud Earrings",
    category: "earrings",
    material: "Stainless Steel, Cubic Zirconia",
    price: 750,
    oldPrice: null,
    image: "images/products/earring-infinity-knot-stud.jpg",
    badge: "",
    featured: false,
    description:
      "Pavé crystal studs woven into an interlocking knot design, finished in silver tone.",
    care: "Hypoallergenic stainless steel posts. Polish gently with a soft cloth.",
    dimensions: "Approx. 1.2 cm."
  },
  {
    id: 24,
    name: "Wave Pearl Hoop Earrings",
    category: "earrings",
    material: "Stainless Steel",
    price: 1550,
    oldPrice: null,
    image: "images/products/earring-wave-pearl-hoop.jpg",
    badge: "New",
    featured: true,
    description:
      "Sculptural two-tone hoops with a rippling wave texture in gold and pearl-white — a statement earring for evening wear.",
    care: "Waterproof and tarnish-resistant. Wipe clean with a soft cloth.",
    dimensions: "Approx. 3.5 cm."
  },
  {
    id: 25,
    name: "Hoop & Stud Starter Collection",
    category: "earrings",
    material: "Stainless Steel, Cubic Zirconia",
    price: 2400,
    oldPrice: null,
    image: "images/products/earring-hoop-stud-collection.jpg",
    badge: "New",
    featured: false,
    description:
      "A curated bundle of hoops and studs — five hoop styles and five tiny stud pairs — perfect for building an everyday earring rotation.",
    care: "Waterproof and tarnish-resistant. Wipe clean with a soft cloth.",
    dimensions: "Sold as a set of 10 pairs."
  },
  {
    id: 26,
    name: "Geometric Stacking Rings",
    category: "rings",
    material: "Stainless Steel",
    price: 80,
    oldPrice: null,
    image: "images/products/ring-geometric-stacking-set.jpg",
    badge: "New",
    featured: false,
    styles: ["Toggle Ring", "Knot Ring", "Triangle Ring", "Fishbone Ring"],
    description:
      "A set of four minimal geometric rings, each sold individually — pick your favourite shape or collect them all for stacking.",
    care: "Waterproof and tarnish-resistant. Wipe clean with a soft cloth.",
    dimensions: "Available in sizes 6–9."
  },
  {
    id: 27,
    name: "CZ Tennis Bracelet",
    category: "bracelets",
    material: "Stainless Steel, Cubic Zirconia",
    price: 250,
    oldPrice: null,
    image: "images/products/bracelet-cz-tennis.jpg",
    badge: "New",
    featured: true,
    styles: ["Clear Stone", "Rainbow Stone"],
    description:
      "A classic pavé tennis bracelet with a secure box clasp — available in clear crystal or a multicolor rainbow stone line.",
    care: "Waterproof and tarnish-resistant. Wipe clean with a soft cloth.",
    dimensions: "Length: 7 inches."
  }
];

const CATEGORY_LABELS = {
  necklaces: "Necklaces",
  earrings: "Earrings",
  bracelets: "Bracelets",
  rings: "Rings",
  sets: "Jewelry Sets"
};

function formatPrice(amount) {
  return "৳" + Number(amount).toLocaleString("en-BD");
}

function getProductById(id) {
  return PRODUCTS.find((p) => String(p.id) === String(id));
}
