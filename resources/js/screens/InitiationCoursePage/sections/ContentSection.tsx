import React, { useRef } from "react";
import gsap from "gsap";
import { useGSAP } from "@gsap/react";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { Card, CardContent } from "../../../components/ui/card";

gsap.registerPlugin(useGSAP, ScrollTrigger);

const contentItems = [
  "Introduction au trading",
  "Fonctionnement des marchés financiers",
  "Types d'actifs financiers",
  "Bases de l'analyse technique",
  "Bases de l'analyse fondamentale",
  "Introduction à la gestion du risque",
  "Erreurs fréquentes des débutants",
];

export const ContentSection = (): JSX.Element => {
  const container = useRef<HTMLDivElement>(null);

  useGSAP(() => {
    // Animation du titre
    gsap.from(".content-title", {
      scrollTrigger: {
        trigger: ".content-title",
        start: "top 90%",
      },
      y: -30,
      opacity: 0,
      duration: 0.8,
      ease: "power2.out",
    });

    // Animation des items de la liste
    gsap.from(".content-item", {
      scrollTrigger: {
        trigger: ".content-grid",
        start: "top 85%",
      },
      x: -20,
      opacity: 0,
      duration: 0.6,
      stagger: 0.1, // Apparition successive rapide
      ease: "power1.out",
    });
  }, { scope: container });

  return (
    <section ref={container} className="flex flex-col items-center px-4 md:px-6 lg:px-8 py-4 md:py-8 lg:py-12 w-full bg-gray-50">
      <div className="max-w-6xl w-full">
        <div className="content-title text-center mb-4 md:mb-8">
          <h2 className="text-2xl md:text-4xl font-bold">
            Contenu de la <span className="text-[#6852d6]">formation</span>
          </h2>
        </div>

        <div className="content-grid grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
          {contentItems.map((item, index) => (
            <Card
              key={index}
              className="content-item border border-gray-300 rounded-2xl hover:shadow-md transition-shadow"
            >
              <CardContent className="p-6 md:p-8 flex items-center gap-4">
                <div className="flex-shrink-0 w-10 h-10 md:w-12 md:h-12 bg-[#6852d6] text-white rounded-full flex items-center justify-center font-bold">
                  {index + 1}
                </div>
                <p className="text-base md:text-lg [font-family:'Sora',Helvetica] text-app-shark">
                  {item}
                </p>
              </CardContent>
            </Card>
          ))}
        </div>
      </div>
    </section>
  );
};