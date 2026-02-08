"use client";

import { useRef } from "react";
import { Card, CardContent } from "../../../components/ui/card";
import { CheckCircle2 } from "lucide-react";
import gsap from "gsap";
import { useGSAP } from "@gsap/react";
import { ScrollTrigger } from "gsap/ScrollTrigger";

// Sécurité pour l'enregistrement du plugin
if (typeof window !== "undefined") {
  gsap.registerPlugin(ScrollTrigger);
}

const benefits = [
  {
    title: "Méthodologie professionnelle",
    description: "Apprenez les techniques utilisées par les traders professionnels, avec une approche structurée et éprouvée sur les marchés.",
  },
  {
    title: "Accompagnement continu",
    description: "Bénéficiez du suivi de nos instructeurs via les Live Class, la communauté VIP et le support personnalisé.",
  },
  {
    title: "Vision long terme",
    description: "Développez une expertise durable avec des compétences transférables sur tous les marchés financiers.",
  },
  {
    title: "Discipline & gestion du risque",
    description: "Maîtrisez la psychologie du trader et les techniques de gestion du risque essentielles à la rentabilité.",
  },
];

export const BenefitsSection = (): JSX.Element => {
  const container = useRef<HTMLDivElement>(null);

  useGSAP(() => {
    if (!container.current) return;

    // Initialisation forcée des états invisibles
    gsap.set(".benefits-title, .benefit-card", {
      opacity: 0,
      y: 40
    });

    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: container.current,
        start: "top 85%", // Déclenchement plus haut pour être sûr que l'utilisateur voit l'anim
        toggleActions: "play none none none",
      },
    });

    tl.to(".benefits-title", {
      y: 0,
      opacity: 1,
      duration: 0.8,
      ease: "power3.out",
    })
      .to(".benefit-card", {
        y: 0,
        opacity: 1,
        duration: 0.7,
        stagger: 0.15,
        ease: "power2.out",
      }, "-=0.4");

  }, { scope: container });

  return (
    <section
      ref={container}
      className="flex flex-col items-center px-4 md:px-6 lg:px-8 py-4 md:py-8 lg:py-12 w-full bg-white overflow-hidden"
    >
      <div className="max-w-6xl w-full">

        {/* opacity-0 par défaut pour éviter le flash de contenu non animé */}
        <div className="benefits-title opacity-0 text-center mb-4 md:mb-8">
          <h2 className="text-2xl md:text-4xl font-bold font-Archivo leading-tight">
            Pourquoi choisir la formation <br className="hidden md:block" />
            <span className="text-[#6852d6]">Devenir Trader Pro</span> ?
          </h2>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10">
          {benefits.map((benefit, index) => (
            <Card
              key={index}
              className="benefit-card opacity-0 group border border-gray-100 rounded-3xl transition-all duration-500 hover:shadow-2xl hover:border-[#6852d6]/20 bg-gray-50/40"
            >
              <CardContent className="p-4 md:p-12 flex flex-col gap-6">
                <div className="flex flex-wrap items-center gap-5 w-full">
                  <div className="p-3 rounded-xl bg-[#6852d6]/10 text-[#6852d6] group-hover:bg-[#6852d6] group-hover:text-white transition-all duration-300 shadow-sm">
                    <CheckCircle2 className="w-7 h-7" />
                  </div>
                  <h3 className="text-xl md:text-2xl font-bold font-Archivo text-gray-900">
                    {benefit.title}
                  </h3>
                </div>

                <p className="text-base md:text-lg font-sora text-gray-600 leading-relaxed border-l-4 border-gray-100 pl-6 group-hover:border-[#6852d6] transition-all duration-300">
                  {benefit.description}
                </p>
              </CardContent>
            </Card>
          ))}
        </div>
      </div>
    </section>
  );
};