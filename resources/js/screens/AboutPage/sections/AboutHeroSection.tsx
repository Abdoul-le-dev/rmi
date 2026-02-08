"use client";

import { useRef } from "react";
import gsap from "gsap";
import { useGSAP } from "@gsap/react";
import { ScrollTrigger } from "gsap/ScrollTrigger";

if (typeof window !== "undefined") {
  gsap.registerPlugin(ScrollTrigger);
}

export const AboutHeroSection = (): JSX.Element => {
  const container = useRef<HTMLDivElement>(null);

  useGSAP(() => {
    if (!container.current) return;

    // État initial forcé
    gsap.set(".about-content", { y: 30, opacity: 0 });
    gsap.set(".about-image", { scale: 1.1, opacity: 0 });

    const tl = gsap.timeline({
      defaults: { ease: "power4.out", duration: 1.2 }
    });

    tl.to(".about-content", {
      y: 0,
      opacity: 1,
      stagger: 0.2,
    })
    .to(".about-image", {
      scale: 1,
      opacity: 1,
      duration: 1.5,
      ease: "expo.out"
    }, "-=0.8");

  }, { scope: container });

  return (
    <section 
      ref={container} 
      className="flex flex-col items-center px-4 md:px-6 lg:px-8 py-16 md:py-24 w-full overflow-hidden bg-white"
    >
      <div className="flex flex-col items-center gap-8 md:gap-12 max-w-5xl">
        
        <div className="flex flex-col items-center gap-6 text-center">
          <h1 className="about-content opacity-0 text-2xl md:text-3xl lg:text-4xl font-bold tracking-tight leading-tight">
            À propos de <span className="bg-gradient-to-r from-[#6852d6] to-[#8a76f0] bg-clip-text text-transparent">RMI Class</span>
          </h1>

          <p className="about-content opacity-0 text-base md:text-xl font-sora text-gray-600 leading-relaxed max-w-3xl">
            Libérez tout votre potentiel dans le trading grâce à des experts hautement qualifiés. 
            Nous prônons une <span className="text-gray-900 font-semibold">discipline de fer</span>, 
            une méthode structurée et une progression durable sur les marchés.
          </p>
        </div>

        <div className="about-image opacity-0 w-full relative group">
          {/* Overlay subtil pour le côté Premium */}
          <div className="absolute inset-0 bg-[#6852d6]/5 rounded-3xl pointer-events-none group-hover:opacity-0 transition-opacity duration-500" />
          
          <img
            className="w-full rounded-3xl object-cover shadow-2xl shadow-gray-200 aspect-video md:max-h-[500px]"
            alt="RMI Class Team"
            src="/about-1.jpg"
          />
        </div>
      </div>
    </section>
  );
};