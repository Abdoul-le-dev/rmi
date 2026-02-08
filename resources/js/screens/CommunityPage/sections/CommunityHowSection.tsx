"use client";

import { useRef } from "react";
import { Card, CardContent } from "../../../components/ui/card";
import gsap from "gsap";
import { useGSAP } from "@gsap/react";
import { ScrollTrigger } from "gsap/ScrollTrigger";

if (typeof window !== "undefined") {
  gsap.registerPlugin(ScrollTrigger);
}

const howItems = [
  {
    number: 1,
    title: "Échanges encadrés",
    description: "Participez aux discussions modérées par nos instructeurs pour une progression saine.",
  },
  {
    number: 2,
    title: "Analyses régulières",
    description: "Recevez des analyses quotidiennes sur Forex et Indices directement dans votre flux.",
  },
  {
    number: 3,
    title: "Accompagnement",
    description: "Bénéficiez du suivi personnalisé de nos coachs pour corriger vos erreurs de trading.",
  },
];

export const CommunityHowSection = (): JSX.Element => {
  const container = useRef<HTMLDivElement>(null);

  useGSAP(() => {
    if (!container.current) return;

    // Configuration initiale
    gsap.set(".how-header", { opacity: 0, y: -20 });
    gsap.set(".how-card", { opacity: 0, scale: 0.95 });
    gsap.set(".step-item", { opacity: 0, y: 30 });

    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: container.current,
        start: "top 80%",
        toggleActions: "play none none none",
      },
    });

    tl.to(".how-header", {
      opacity: 1,
      y: 0,
      duration: 0.8,
      ease: "power3.out",
    })
    .to(".how-card", {
      opacity: 1,
      scale: 1,
      duration: 1,
      ease: "expo.out",
    }, "-=0.4")
    .to(".step-item", {
      opacity: 1,
      y: 0,
      duration: 0.7,
      stagger: 0.3, // Délai marqué pour bien montrer l'ordre 1 -> 2 -> 3
      ease: "power2.out",
    }, "-=0.6");

  }, { scope: container });

  return (
    <section 
      ref={container} 
      className="flex flex-col items-center px-4 md:px-6 lg:px-8 py-16 md:py-24 w-full bg-white overflow-hidden"
    >
      <div className="max-w-6xl w-full">
        
        <div className="how-header opacity-0 text-center mb-12 md:mb-16">
          <h2 className="text-xl md:text-4xl font-bold tracking-tight font-Archivo">
            Comment fonctionne la <span className="text-[#6852d6]">communauté</span> ?
          </h2>
        </div>

        <Card className="how-card opacity-0 border border-gray-100 rounded-[2.5rem] shadow-2xl shadow-gray-200/50 bg-white relative overflow-hidden">
          {/* Décoration abstraite en arrière-plan de la carte */}
          <div className="absolute top-0 right-0 w-64 h-64 bg-[#6852d6]/5 rounded-full -mr-32 -mt-32 blur-3xl" />
          
          <CardContent className="p-10 md:p-16 relative z-10">
            <div className="grid grid-cols-1 md:grid-cols-3 gap-12 md:gap-16 relative">
              
              {/* Ligne de connexion entre les étapes (Desktop uniquement) */}
              <div className="hidden md:block absolute top-10 left-[15%] right-[15%] h-[2px] bg-gradient-to-r from-transparent via-gray-100 to-transparent" />

              {howItems.map((item, index) => (
                <div key={index} className="step-item opacity-0 flex flex-col items-center text-center gap-6 group">
                  
                  {/* Bulle de numéro avec effet de halo */}
                  <div className="relative">
                    <div className="absolute inset-0 bg-[#6852d6] rounded-full blur-md opacity-0 group-hover:opacity-40 transition-opacity duration-500" />
                    <div className="w-16 h-16 rounded-full bg-gradient-to-br from-[#6852d6] to-[#8a7ae3] text-white flex items-center justify-center font-bold text-2xl shadow-xl relative z-10 transition-transform duration-500 group-hover:scale-110">
                      {item.number}
                    </div>
                  </div>

                  <div className="space-y-3">
                    <h3 className="text-xl md:text-2xl font-bold font-Archivo text-gray-900">
                      {item.title}
                    </h3>
                    <p className="text-base font-sora text-gray-500 leading-relaxed px-4">
                      {item.description}
                    </p>
                  </div>

                </div>
              ))}
            </div>
          </CardContent>
        </Card>
      </div>
    </section>
  );
};