import React, { useRef } from "react";
import gsap from "gsap";
import { useGSAP } from "@gsap/react";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { Card, CardContent } from "../../../components/ui/card";
import { Globe, BookOpen, Shield, ChartBar } from "lucide-react";

gsap.registerPlugin(useGSAP, ScrollTrigger);

const objectives = [
  {
    icon: <Globe />,
    title: "Comprendre le fonctionnement des marchés financiers",
  },
  {
    icon: <BookOpen />,
    title: "Apprendre le vocabulaire essentiel du trading",
  },
  {
    icon: <ChartBar />,
    title: "Identifier les différents types de marchés (Forex, Indices, crypto, matières premières)",
  },
  {
    icon: <Shield />,
    title: "Comprendre le rôle du trader et la gestion du risque",
  },
];

export const ObjectivesSection = (): JSX.Element => {
  const container = useRef<HTMLDivElement>(null);

  useGSAP(() => {
    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: container.current,
        start: "top 80%", // Déclenche l'animation quand la section arrive à 80% de l'écran
        toggleActions: "play none none none",
      },
    });

    // 1. Animation du titre principal
    tl.from(".obj-title", {
      y: -20,
      opacity: 0,
      duration: 0.8,
      ease: "power2.out",
    });

    // 2. Animation des cartes d'objectifs
    tl.from(".obj-card", {
      scale: 0.95,
      opacity: 0,
      duration: 0.6,
      stagger: 0.15, // Apparition séquentielle
      ease: "power2.out",
    }, "-=0.4"); // Commence un peu avant la fin de l'animation du titre

    // 3. Animation spécifique des icônes pour un effet "pop"
    tl.from(".obj-icon", {
      rotation: -15,
      scale: 0,
      duration: 0.5,
      stagger: 0.15,
      ease: "back.out(1.7)",
    }, "-=0.6");

  }, { scope: container });

  return (
    <section ref={container} className="flex flex-col items-center px-4 md:px-6 lg:px-8 py-4 md:py-8 lg:py-12 w-full overflow-hidden">
      <div className="max-w-6xl w-full">
        <div className="obj-title text-center mb-4 md:mb-8">
          <h2 className="text-2xl md:text-4xl font-bold">
            Objectifs de la <span className="text-[#6852d6]">formation</span>
          </h2>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
          {objectives.map((objective, index) => (
            <Card key={index} className="obj-card border border-gray-300 rounded-2xl hover:border-[#6852d6] transition-colors duration-300">
              <CardContent className="p-6 md:p-8 flex justify-start items-start text-start gap-4">
                <div className="obj-icon w-10 h-10 shrink-0 bg-[#6852d6] text-white rounded-md flex justify-center items-center text-center">
                  {objective.icon}
                </div>
                <h3 className="text-md text-app-shark [font-family:'Sora',Helvetica] font-medium leading-relaxed">
                  {objective.title}
                </h3>
              </CardContent>
            </Card>
          ))}
        </div>
      </div>
    </section>
  );
};