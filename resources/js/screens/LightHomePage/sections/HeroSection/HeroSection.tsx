"use client";

import { useRef } from "react";
import { Trophy, Users } from "lucide-react";
import { Button } from "../../../../components/ui/button";
import gsap from "gsap";
import { useGSAP } from "@gsap/react";
import { useNavigate } from "react-router-dom";

gsap.registerPlugin(useGSAP);

export const HeroSection = (): JSX.Element => {
  const container = useRef<HTMLDivElement>(null);
  const navigate = useNavigate();

  useGSAP(() => {
    const tl = gsap.timeline({ defaults: { ease: "power3.out" } });

    // 1. Titre (animation par span pour l'effet de cascade)
    tl.from(".hero-title span", {
      opacity: 0,
      y: 30,
      duration: 0.8,
      stagger: 0.15,
    })
      // 2. Soulignement (effet de dessin de gauche à droite)
      .from(".hero-underline", {
        scaleX: 0,
        transformOrigin: "left",
        duration: 0.8,
      }, "-=0.4")
      // 3. Image principale (effet de focus)
      .from(".hero-image", {
        opacity: 0,
        scale: 0.96,
        duration: 1,
      }, "-=0.6")
      // 4. Texte de description
      .from(".hero-text", {
        opacity: 0,
        y: 20,
        duration: 0.6,
      }, "-=0.6")
      // 5. Boutons CTA (Entrée synchronisée avec un petit rebond)
      .from(".hero-cta", {
        opacity: 0,
        y: 20,
        duration: 0.6,
        stagger: 0.15,
        ease: "back.out(1.7)",
        clearProps: "all" // Important pour laisser le hover CSS (Tailwind) fonctionner
      }, "-=0.4");

  }, { scope: container });

  return (
    <section
      ref={container}
      className="flex flex-col items-center gap-10 md:gap-14 px-4 md:px-6 lg:px-8 pt-14 md:pt-20 pb-8 w-full overflow-hidden"
    >
      <div className="flex flex-col items-center w-full max-w-5xl mx-auto text-center gap-6">

        {/* Title & Underline */}
        <div className="flex flex-col items-center gap-2 w-full">
          <h1 className="hero-title text-lg md:text-xl lg:text-3xl font-bold leading-tight w-full">
            <span className="text-[#6852d6] inline-block mr-2">RMI Class,</span>
            <span className="text-black inline-block">
              nous bâtissons l’expertise rentable et durable
            </span>
            <span className="block text-black">

            </span>
          </h1>

          <img
            className="hero-underline w-full max-w-[260px] md:max-w-[320px]"
            alt="Underline"
            src="/vector-32.svg"
          />
        </div>

        {/* Hero Image */}
        <div className="w-full flex justify-center">
          <img
            className="hero-image w-full max-w-2xl md:max-w-3xl rounded-xl object-cover"
            alt="Products showcase"
            src="/3-1.png"
          />
        </div>

        {/* Description */}
        <p className="hero-text max-w-2xl md:max-w-3xl text-sm md:text-base text-gray-600 [font-family:'Sora',Helvetica] leading-relaxed">
          Un écosystème d’excellence, accessible aux profils disposant d’une capacité financière suffisante,  soutenue par un accompagnement sur mesure et une étude approfondie des marchés.
        </p>

        {/* CTA Buttons */}
        <div className="flex flex-col sm:flex-row gap-4 w-full max-w-2xl">
          <Button
            size="lg"
            onClick={() => navigate("/devenir-trader-pro")}
            className="hero-cta flex group flex-1 bg-[#6852d6] hover:bg-[#5841c5] text-md rounded-xl py-4 h-auto font-semibold text-white transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5"
          >
            <Trophy className="w-5 h-5 mr-2 transition-transform group-hover:scale-110" />
            <span>Rejoignez le Top 1% maintenant</span>
          </Button>

          <Button
            size="lg"
            onClick={() => navigate("/devenir-trader-pro")}
            variant="outline"
            className="hero-cta group flex flex-1 rounded-xl py-4 h-auto text-md font-semibold border-gray-300 transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5"
          >
            <Users className="w-5 h-5 mr-2 transition-transform group-hover:scale-110" />
            <span>Obtenez Accès exclusif</span>
          </Button>
        </div>

      </div>
    </section>
  );
};