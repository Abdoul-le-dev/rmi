"use client";

import { useRef } from "react";
import gsap from "gsap";
import { useGSAP } from "@gsap/react";
import { ScrollTrigger } from "gsap/ScrollTrigger";

if (typeof window !== "undefined") {
  gsap.registerPlugin(ScrollTrigger);
}

export const BeforeStartingSection = (): JSX.Element => {
  const container = useRef<HTMLDivElement>(null);

  useGSAP(() => {
    if (!container.current) return;

    // Initialisation des états invisibles
    gsap.set(".book-container", { x: -50, opacity: 0, rotateY: 15 });
    gsap.set(".content-item", { x: 30, opacity: 0 });

    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: container.current,
        start: "top 80%",
        toggleActions: "play none none none",
      },
    });

    tl.to(".book-container", {
      x: 0,
      opacity: 1,
      rotateY: 0,
      duration: 1.2,
      ease: "power4.out",
    })
    .to(".content-item", {
      x: 0,
      opacity: 1,
      duration: 0.8,
      stagger: 0.2,
      ease: "power3.out",
    }, "-=0.6");

  }, { scope: container });

  return (
    <section 
      ref={container} 
      className="flex flex-col items-center px-4 md:px-6 lg:px-8 py-16 md:py-24 w-full bg-white overflow-hidden"
    >
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 md:gap-16 max-w-6xl items-center">
        
        {/* Conteneur Image avec perspective */}
        <div className="book-container opacity-0 flex justify-center items-center overflow-hidden bg-white p-2 shadow-2xl border border-gray-100 rounded-[2rem] perspective-1000">
          <img
            className="w-full h-[500px] max-h-[600px] rounded-2xl object-cover hover:scale-105 transition-transform duration-700"
            alt="Before Starting Trading Book"
            src="/livre.jpg"
          />
        </div>

        <div className="flex flex-col gap-8">
          <div className="space-y-4">
            <h2 className="content-item opacity-0 text-2xl md:text-3xl font-bold text-[#6852d6] leading-tight font-Archivo">
              AVANT DE VOUS LANCER <br /> EN TRADING
            </h2>
            <div className="content-item opacity-0 w-20 h-1.5 bg-[#6852d6] rounded-full" />
          </div>

          <p className="content-item opacity-0 text-base md:text-md font-sora text-gray-700 leading-relaxed italic">
            "Ce livre vous prépare au métier de Trader Professionnel. J'y ai mis tous les outils et connaissances nécessaires pour vous permettre de vous lancer en toute confiance."
          </p>

          <p className="content-item opacity-0 text-base md:text-md font-sora text-gray-600 leading-8">
            Du haut de <span className="text-gray-900 font-bold underline decoration-[#6852d6]">8 années</span> en tant que Trader professionnel et 2 années en tant que Coach de réputation, j'ai compilé les réponses à toutes vos questions. Passez enfin du rêve à la réalité.
          </p>

          <div className="content-item opacity-0 space-y-4 bg-gray-50 p-6 md:p-8 rounded-2xl border-l-4 border-[#6852d6] shadow-sm">
            <blockquote className="italic text-base md:text-md font-sora text-gray-800 leading-relaxed">
              "Ma mission est d'aider les entrepreneurs en herbe à créer des entreprises durables qui leur apportent liberté financière et flexibilité."
            </blockquote>
            <div className="flex flex-col">
              <span className="font-bold text-[#6852d6] font-Archivo">Flacre D'KPANOU</span>
              <span className="text-sm text-gray-500 font-sora">Auteur & Coach Expert</span>
            </div>
          </div>
          
          <p className="content-item opacity-0 text-sm md:text-base font-semibold text-gray-400 font-sora uppercase tracking-widest">
            Notre mission est de vous préparer au succès.
          </p>
        </div>
      </div>
    </section>
  );
};