"use client";

import { useRef } from "react";
import { Button } from "../../../components/ui/button";
import { Badge } from "../../../components/ui/badge";
import { Award, Users, ArrowRight } from "lucide-react";
import gsap from "gsap";
import { useGSAP } from "@gsap/react";

export const CourseHeroSection = (): JSX.Element => {
  const container = useRef<HTMLDivElement>(null);

  useGSAP(() => {
    const tl = gsap.timeline({ defaults: { ease: "power4.out", duration: 1 } });

    // 1. Apparition du Badge (zoom léger)
    tl.from(".hero-badge", {
      scale: 0.8,
      opacity: 0,
      duration: 0.8,
    })
    // 2. Texte principal (remontée + opacité)
    .from(".hero-title", {
      y: 40,
      opacity: 0,
    }, "-=0.6")
    // 3. Description (glissement latéral léger)
    .from(".hero-desc", {
      y: 20,
      opacity: 0,
    }, "-=0.7")
    // 4. Boutons (échelle avec rebond)
    .from(".hero-btns", {
      scale: 0.9,
      opacity: 0,
      stagger: 0.2,
      ease: "back.out(1.7)",
    }, "-=0.6");

    // Animation de pulsation continue sur le bouton principal
    gsap.to(".btn-glow", {
      boxShadow: "0 0 20px rgba(104, 82, 214, 0.4)",
      repeat: -1,
      yoyo: true,
      duration: 1.5,
    });

  }, { scope: container });

  return (
    <section ref={container} className="relative flex flex-col items-center px-4 md:px-6 lg:px-8 py-4 md:py-8 lg:py-16 w-full overflow-hidden bg-[radial-gradient(circle_at_top,_var(--tw-gradient-stops))] from-[#6852d6]/5 via-white to-white">
      <div className="flex flex-col items-center gap-8 md:gap-10 w-full max-w-4xl">
        
        <Badge className="hero-badge bg-[#6852d6] text-white px-6 py-2 rounded-full font-sora shadow-lg shadow-[#6852d6]/20 border-none">
          ✨ Formation phare
        </Badge>

        <h1 className="hero-title text-2xl md:text-4xl lg:text-6xl font-bold text-center tracking-tight leading-[1.1]">
          Devenir <span className="bg-gradient-to-r from-[#6852d6] via-[#8a76f0] to-[#6852d6] bg-clip-text text-transparent bg-[length:200%_auto] animate-gradient-x">Trader Pro</span>
        </h1>

        <p className="hero-desc text-base md:text-xl text-center font-sora text-gray-600 leading-relaxed max-w-2xl">
          Une formation complète pour structurer votre approche du trading et évoluer avec une <span className="text-gray-900 font-semibold">méthodologie institutionnelle</span> et une discipline de fer.
        </p>

        <div className="hero-btns flex flex-col sm:flex-row gap-4 w-full justify-center items-center">
          <Button size="lg" className="btn-glow group relative flex items-center gap-2 bg-[#6852d6] hover:bg-[#5841c5] w-full md:w-auto rounded-xl px-8 py-4 h-auto text-xs md:text-lg font-bold transition-all hover:scale-105 active:scale-95 shadow-xl shadow-[#6852d6]/25">
            <Award className="w-6 h-6" />
            Rejoignez le Top 1% maintenant
            <ArrowRight className="w-5 h-5 group-hover:translate-x-1 transition-transform" />
          </Button>

          <Button size="lg" variant="outline" className="flex items-center gap-2  w-full md:w-auto rounded-xl px-8 py-4 h-auto text-xs md:text-lg font-bold border-2 border-gray-200 hover:border-[#6852d6] hover:text-[#6852d6] transition-all font-sora">
            <Users className="w-6 h-6" />
            Obtenir l'accès exclusif
          </Button>
        </div>
      </div>

      {/* Décoration d'arrière-plan discrète */}
      <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-[#6852d6]/5 rounded-full blur-3xl -z-10" />
    </section>
  );
};