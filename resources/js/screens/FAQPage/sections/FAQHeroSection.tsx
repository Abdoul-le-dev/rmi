"use client";

import { useRef } from "react";
import gsap from "gsap";
import { useGSAP } from "@gsap/react";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(useGSAP, ScrollTrigger);

export const FAQHeroSection = (): JSX.Element => {
  const container = useRef<HTMLDivElement>(null);

  useGSAP(() => {
    const tl = gsap.timeline({
      defaults: { ease: "power3.out", duration: 0.8 }
    });

    // 1. Animation du titre avec un léger scale
    tl.from(".faq-title", {
      y: 30,
      opacity: 0,
      scale: 0.98,
    })
    // 2. Animation du paragraphe avec un léger retard
    .from(".faq-description", {
      y: 20,
      opacity: 0,
    }, "-=0.5");

  }, { scope: container });

  return (
    <section 
      ref={container} 
      className="flex flex-col items-center px-4 md:px-6 lg:px-8 py-8 md:py-12 lg:py-16 w-full overflow-hidden"
    >
      <div className="flex flex-col items-center gap-6 md:gap-8 max-w-4xl">
        <h1 className="faq-title text-2xl md:text-4xl lg:text-6xl font-bold text-center tracking-tight">
          Foire Aux <span className="bg-gradient-to-r from-[#6852d6] to-[#8a76f0] bg-clip-text text-transparent">Questions</span>
        </h1>

        <p className="faq-description text-base md:text-lg text-center font-sora text-gray-600 leading-relaxed max-w-2xl">
          Retrouvez ici les réponses aux questions les plus fréquemment posées sur <span className="font-semibold text-[#6852d6]">RMI Class</span>, nos formations et notre accompagnement.
        </p>
      </div>
    </section>
  );
};