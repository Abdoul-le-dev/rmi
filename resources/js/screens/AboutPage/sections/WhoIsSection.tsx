"use client";

import { useRef } from "react";
import gsap from "gsap";
import { useGSAP } from "@gsap/react";
import { ScrollTrigger } from "gsap/ScrollTrigger";

if (typeof window !== "undefined") {
  gsap.registerPlugin(ScrollTrigger);
}

export const WhoIsSection = (): JSX.Element => {
  const container = useRef<HTMLDivElement>(null);

  useGSAP(() => {
    if (!container.current) return;

    // Initialisation forcée des états invisibles
    gsap.set(".who-text", { y: 40, opacity: 0 });
    gsap.set(".who-image", { clipPath: "inset(0% 100% 0% 0%)", opacity: 0 });

    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: container.current,
        start: "top 80%",
        toggleActions: "play none none none",
      },
    });

    tl.to(".who-text", {
      y: 0,
      opacity: 1,
      duration: 0.8,
      stagger: 0.15,
      ease: "power3.out",
    })
    .to(".who-image", {
      clipPath: "inset(0% 0% 0% 0%)",
      opacity: 1,
      duration: 1.2,
      ease: "expo.inOut"
    }, "-=0.6");

  }, { scope: container });

  return (
    <section ref={container} className="flex flex-col items-center px-4 md:px-6 lg:px-8 py-16 md:py-24 w-full bg-gray-50/50 overflow-hidden">
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 max-w-6xl items-center">
        
        {/* Texte : Ordre 2 sur mobile, 1 sur desktop */}
        <div className="flex flex-col gap-8 order-2 lg:order-1">
          <div className="who-text opacity-0 space-y-4">
            <h2 className="text-2xl md:text-4xl font-bold tracking-tight leading-tight font-Archivo">
              Qui est <span className="bg-gradient-to-r from-[#6852d6] to-[#8a76f0] bg-clip-text text-transparent">RMI Class</span> ?
            </h2>
            <div className="w-16 h-1 bg-[#6852d6] rounded-full" />
          </div>

          <p className="who-text opacity-0 text-base md:text-lg font-sora text-gray-600 leading-relaxed italic">
            "RMI Class est bien plus qu'une simple plateforme de formation. C'est un écosystème complet conçu pour accompagner les traders à chaque étape."
          </p>

          <div className="who-text opacity-0 space-y-8">
            <div className="bg-white p-8 rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100">
              <h3 className="font-bold text-gray-900 font-Archivo text-xl mb-6 flex items-center gap-3">
                <span className="w-2 h-8 bg-[#6852d6] rounded-full" />
                Nos piliers fondamentaux
              </h3>
              
              <ul className="space-y-6">
                <li className="flex gap-4">
                  <div className="flex-shrink-0 w-6 h-6 rounded-full bg-[#6852d6]/10 flex items-center justify-center mt-1">
                    <div className="w-2 h-2 rounded-full bg-[#6852d6]" />
                  </div>
                  <p className="text-sm md:text-base font-sora text-gray-600">
                    <strong className="text-gray-900">L'éducation & la discipline :</strong> Nous croyons que la performance durable ne s'improvise pas, elle se construit.
                  </p>
                </li>
                <li className="flex gap-4">
                  <div className="flex-shrink-0 w-6 h-6 rounded-full bg-[#6852d6]/10 flex items-center justify-center mt-1">
                    <div className="w-2 h-2 rounded-full bg-[#6852d6]" />
                  </div>
                  <p className="text-sm md:text-base font-sora text-gray-600">
                    <strong className="text-gray-900">Vision Long Terme :</strong> Loin des promesses irréalistes, nous privilégions la compréhension profonde des marchés.
                  </p>
                </li>
              </ul>
            </div>
          </div>
        </div>

        {/* Image : Ordre 1 sur mobile, 2 sur desktop */}
        <div className="order-1 lg:order-2 flex justify-center items-center">
          <div className="who-image opacity-0 relative group">
            {/* Effet de décoration derrière l'image */}
            <div className="absolute -bottom-6 -right-6 w-full h-full border-2 border-[#6852d6]/20 rounded-2xl -z-10 group-hover:-translate-x-2 group-hover:-translate-y-2 transition-transform duration-500" />
            
            <img
              className="w-full h-auto rounded-2xl shadow-2xl object-cover grayscale-[20%] hover:grayscale-0 transition-all duration-700"
              alt="RMI Class Workspace"
              src="/about-2.jpg"
            />
          </div>
        </div>
        
      </div>
    </section>
  );
};