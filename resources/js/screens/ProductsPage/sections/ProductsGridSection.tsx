import { Card, CardContent } from "../../../components/ui/card";
import { Badge } from "../../../components/ui/badge";
import { Button } from "../../../components/ui/button";
import { useEffect, useRef, useState } from "react";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import {Filter} from "lucide-react"

gsap.registerPlugin(ScrollTrigger);

const products = [
  {
    image: "/1.jpg",
    badge: "Gratuit",
    badgeColor: "bg-green-500",
    category: "Formations • Débutant",
    title: "Initiation au Trading",
    description:
      "Les bases solides pour comprendre et maîtriser les marchés financiers.",
    price: null,
    buttonText: "Découvrir →",
  },
  {
    image: "/2.jpg",
    badge: "Populaire",
    badgeColor: "bg-blue-500",
    category: "Formations • Avancé",
    title: "Devenir Trader Pro",
    description:
      "Formation complète avec 101+ modules, examens et accès à vie.",
    price: "299€",
    originalPrice: "499€",
    buttonText: "Acheter maintenant →",
  },
  {
    image: "/3.jpg",
    category: "Modules • Intermédiaire",
    title: "Pack Live Class",
    description:
      "Accès aux sessions live et analyses quotidiennes pendant 3 mois.",
    price: "99€",
    buttonText: "Acheter maintenant →",
  },
  {
    image: "/4.jpg",
    badge: "Nouveau",
    badgeColor: "bg-purple-500",
    category: "Livres • Tous niveaux",
    title: "Guide Psychologie du Trader",
    description:
      "Ebook complet sur la maîtrise mentale en trading de façon professionnelle",
    price: "29€",
    buttonText: "Acheter maintenant →",
  },
  {
    image: "/5.jpg",
    category: "Ressources • Intermédiaire",
    title: "Templates d'Analyse",
    description:
      "Pack de templates pour structurer vos analyses techniques.",
    price: "19€",
    buttonText: "Acheter maintenant →",
  },
  {
    image: "/about-1.jpg",
    badge: "Recommandé",
    badgeColor: "bg-orange-500",
    category: "Communautés • Avancé",
    title: "Communauté VIP - 1 an",
    description:
      "Accès complet à la communauté VIP pendant 12 mois",
    price: "199€",
    originalPrice: "299€",
    buttonText: "Acheter maintenant →",
  },
];

const tabs = ["Tous", "Formations", "Modules", "Livres", "Ressources"];

export const ProductsGridSection = (): JSX.Element => {
  const [activeTab, setActiveTab] = useState("Tous");
  const sectionRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    if (!sectionRef.current) return;

    const ctx = gsap.context(() => {
      gsap.fromTo(
        ".product-card",
        { opacity: 0, y: 40 },
        {
          opacity: 1,
          y: 0,
          duration: 0.8,
          ease: "power3.out",
          stagger: 0.12,
          scrollTrigger: {
            trigger: sectionRef.current,
            start: "top 75%",
          },
        }
      );
    }, sectionRef);

    return () => ctx.revert();
  }, []);

  return (
    <section
      ref={sectionRef}
      className="flex flex-col items-center px-4 md:px-6 lg:px-8 py-12 md:py-16 lg:py-20 w-full bg-gray-50"
    >
      <div className="max-w-7xl w-full">
        {/* Tabs */}
        <div className="flex flex-col md:flex-row items-center justify-between gap-6 mb-12">
          <div className="grid grid-cols-2 md:flex md:justify-start md:flex-wrap gap-2 w-full md:w-auto">
            {tabs.map((tab) => (
              <button
                key={tab}
                onClick={() => setActiveTab(tab)}
                className={`px-4 py-2 rounded-full font-semibold [font-family:'Sora',Helvetica] transition-all duration-300 ${
                  activeTab === tab
                    ? "bg-[#6852d6] text-white shadow-md"
                    : "bg-white text-app-shark border border-gray-300 hover:border-[#6852d6]"
                }`}
              >
                {tab}
              </button>
            ))}
          </div>

          <div className="w-full md:w-auto flex justify-start items-start gap-2 text-sm md:text-base [font-family:'Sora',Helvetica] text-app-shark bg-white text-app-shark border border-gray-300 hover:border-[#6852d6] px-4 py-2 rounded-xl">
            <Filter className="w-5 h-5"/>
            <span>Popularité</span>
          </div>
        </div>

        {/* Grid */}
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
          {products.map((product, index) => (
            <Card
              key={index}
              className="product-card rounded-2xl overflow-hidden border border-gray-200 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl"
            >
              <CardContent className="p-0 flex flex-col h-full">
                <div className="relative aspect-video overflow-hidden bg-gray-300">
                  <img
                    src={product.image}
                    alt={product.title}
                    className="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
                  />
                  {product.badge && (
                    <Badge
                      className={`absolute top-4 right-4 ${product.badgeColor} text-white px-3 py-1 rounded-full font-[Sora] text-xs`}
                    >
                      {product.badge}
                    </Badge>
                  )}
                </div>

                <div className="p-6 md:p-8 flex flex-col gap-4 flex-grow">
                  <p className="text-xs uppercase tracking-wide text-gray-500">
                    {product.category}
                  </p>

                  <h3 className="text-lg md:text-xl font-bold [font-family:'Archivo',Helvetica]">
                    {product.title}
                  </h3>

                  <p className="text-sm md:text-base text-gray-600 flex-grow">
                    {product.description}
                  </p>

                  <div className="flex items-center gap-2">
                    {product.price ? (
                      <>
                        <span className="text-2xl font-bold text-[#6852d6]">
                          {product.price}
                        </span>
                        {product.originalPrice && (
                          <span className="text-sm text-gray-400 line-through">
                            {product.originalPrice}
                          </span>
                        )}
                      </>
                    ) : (
                      <span className="text-2xl font-bold text-green-600">
                        Gratuit
                      </span>
                    )}
                  </div>

                  <Button className="w-full bg-[#6852d6] hover:bg-[#5841c5] rounded-lg py-3 font-semibold transition-all hover:shadow-lg">
                    {product.buttonText}
                  </Button>
                </div>
              </CardContent>
            </Card>
          ))}
        </div>
      </div>
    </section>
  );
};
