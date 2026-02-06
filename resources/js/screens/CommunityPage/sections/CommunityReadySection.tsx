"use client";

import { useRef } from "react";
import { Button } from "../../../components/ui/button";
import gsap from "gsap";
import { useGSAP } from "@gsap/react";
import { ScrollTrigger } from "gsap/ScrollTrigger";

if (typeof window !== "undefined") {
  gsap.registerPlugin(ScrollTrigger);
}

export const CommunityReadySection = (): JSX.Element => {
  const container = useRef<HTMLDivElement>(null);

  useGSAP(() => {
    if (!container.current) return;

    // État initial discret
    gsap.set(".cta-content", { y: 30, opacity: 0 });
    gsap.set(".cta-button", { scale: 0.9, opacity: 0 });

    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: container.current,
        start: "top 85%",
        toggleActions: "play none none none",
      },
    });

    tl.to(".cta-content", {
      y: 0,
      opacity: 1,
      duration: 0.8,
      stagger: 0.2,
      ease: "power3.out",
    })
    .to(".cta-button", {
      scale: 1,
      opacity: 1,
      duration: 0.6,
      ease: "back.out(1.7)", // Petit rebond pour attirer l'attention
    }, "-=0.2");

  }, { scope: container });

  return (
    <section 
      ref={container} 
      className="relative flex flex-col items-center px-4 md:px-6 lg:px-8 py-20 md:py-32 w-full overflow-hidden"
    >
      {/* Background Decoratif pour le côté Premium */}
      <div className="absolute inset-0 bg-gradient-to-b from-white to-gray-50/50 -z-10" />
      <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-[#6852d6]/5 rounded-full blur-[120px] -z-10" />

      <div className="flex flex-col items-center gap-8 max-w-3xl text-center md:w-auto w-full">
        <h2 className="cta-content opacity-0 text-2xl md:text-4xl font-bold tracking-tight font-Archivo leading-tight">
          Prêt à rejoindre la communauté <br />
          <span className="text-[#6852d6]">RMI Class</span> ?
        </h2>

        <p className="cta-content opacity-0 text-base md:text-xl font-sora text-gray-600 leading-relaxed max-w-xl">
          Accédez dès maintenant aux analyses, aux Live Class et à un entourage d'élite pour propulser vos résultats.
        </p>

        <div className="cta-button opacity-0 pt-4 md:w-auto w-full">
          <Button size="lg" className="bg-[#6852d6] hover:bg-[#5841c5] rounded-2xl px-10 py-4 h-auto w-full md:w-auto text-xs md:text-lg font-bold font-sora transition-all shadow-2xl shadow-[#6852d6]/30 hover:shadow-[#6852d6]/50 hover:-translate-y-1 active:scale-95">
            Rejoindre la communauté dès maintenant
          </Button>
          
          {/* Texte de rassurance sous le bouton */}
          <p className="mt-6 text-sm text-gray-400 font-sora">
            Rejoignez plus de 2,500 traders passionnés. Accès immédiat.
          </p>
        </div>
      </div>
    </section>
  );
};