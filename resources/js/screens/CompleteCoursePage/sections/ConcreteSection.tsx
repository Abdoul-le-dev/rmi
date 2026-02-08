"use client";

import { useRef } from "react";
import { Card, CardContent } from "../../../components/ui/card";
import { CheckCircle2 } from "lucide-react";
import gsap from "gsap";
import { useGSAP } from "@gsap/react";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(useGSAP, ScrollTrigger);

const concreteItems = [
  "Accès à l'ensemble des modules",
  "Communauté privée VIP",
  "Support et accompagnement personnalisé",
  "Live réguliers avec les instructeurs",
  "Outils et templates professionnels",
  "Certification officielle RMI Class",
];

export const ConcreteSection = (): JSX.Element => {
  const container = useRef<HTMLDivElement>(null);

  useGSAP(() => {
    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: container.current,
        start: "top 80%",
        toggleActions: "play none none none",
      },
    });

    // 1. Animation du titre
    tl.from(".concrete-title", {
      y: 20,
      opacity: 0,
      duration: 0.6,
    })
      // 2. Animation de la carte parente
      .from(".concrete-card", {
        y: 30,
        opacity: 0,
        duration: 0.8,
        ease: "power3.out",
      }, "-=0.3")
      // 3. Animation des items (stagger + scale)
      .from(".concrete-item", {
        x: -20,
        opacity: 0,
        duration: 0.5,
        stagger: 0.1,
        ease: "back.out(1.7)",
      }, "-=0.4");

  }, { scope: container });

  return (
    <section ref={container} className="flex flex-col items-center px-4 md:px-6 lg:px-8 py-4 md:py-8 lg:py-12 w-full bg-gray-50/50 overflow-hidden">
      <div className="max-w-4xl w-full">

        <div className="concrete-title text-center mb-4 md:mb-8">
          <h2 className="text-2xl md:text-3xl lg:text-4xl font-bold tracking-tight">
            Ce que vous obtenez <span className="text-[#6852d6]">concrètement</span>
          </h2>
        </div>

        <Card className="concrete-card border border-gray-100 rounded-3xl shadow-xl shadow-gray-200/50 bg-white">
          <CardContent className="p-8 md:p-14">
            <div className="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
              {concreteItems.map((item, index) => (
                <div
                  key={index}
                  className="concrete-item flex items-center gap-4 group"
                >
                  <div className="flex-shrink-0 w-8 h-8 rounded-full bg-[#6852d6]/10 flex items-center justify-center group-hover:bg-[#6852d6] transition-colors duration-300">
                    <CheckCircle2 className="w-5 h-5 text-[#6852d6] group-hover:text-white transition-colors duration-300" />
                  </div>
                  <span className="text-base md:text-lg font-sora text-gray-700 font-medium">
                    {item}
                  </span>
                </div>
              ))}
            </div>
          </CardContent>
        </Card>
      </div>
    </section>
  );
};