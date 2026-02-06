"use client";

import { useRef } from "react";
import { Button } from "../../../components/ui/button";
import { Award } from "lucide-react";
import gsap from "gsap";
import { useGSAP } from "@gsap/react";

const instructors = [
  {
    name: "Fiacre KPANOU",
    role: "Founder & Coach",
    badge: "Fondateur",
    image: "/fiacre.jpg",
    socials: ["linkedin", "twitter"],
  },
  {
    name: "Charbel Yayi",
    role: "Trader Indépendant",
    badge: undefined,
    image: "/charbel.jpg",
    socials: ["linkedin", "twitter"],
  },
  {
    name: "Hugues HOUNSA",
    role: "Trader Indépendant",
    badge: undefined,
    image: "/hugues.jpg",
    socials: ["linkedin", "twitter"],
  },
  {
    name: "Junice Y. HOUNGUE",
    role: "Coach",
    badge: "Coach certifié",
    image: "/junice.jpg",
    socials: ["linkedin", "twitter"],
  },
];


export const CommunityHeroSection = (): JSX.Element => {
  const container = useRef<HTMLDivElement>(null);

  useGSAP(() => {
    if (!container.current) return;

    // État initial forcé pour éviter le flash de contenu
    gsap.set(".hero-element", { y: 40, opacity: 0 });

    const tl = gsap.timeline({
      defaults: { ease: "power4.out", duration: 1 }
    });

    tl.to(".hero-element", {
      y: 0,
      opacity: 1,
      stagger: 0.15, // Chaque élément suit l'autre
    });

    // Animation de flottement infinie sur l'icône Award pour attirer l'œil
    gsap.to(".award-icon", {
      y: -4,
      repeat: -1,
      yoyo: true,
      duration: 2,
      ease: "sine.inOut"
    });

  }, { scope: container });

  return (
    <section 
      ref={container} 
      className="relative flex flex-col items-center px-4 md:px-6 lg:px-8 py-4 md:py-8 lg:py-16 w-full overflow-hidden bg-white"
    >
      {/* Cercles de décoration en arrière-plan pour le côté "Élite" */}
      <div className="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-[#6852d6]/5 rounded-full blur-3xl -z-10" />

      <div className="flex flex-col items-center justify-center gap-8 md:gap-10 w-full max-w-4xl">
        
        <div className="hero-element opacity-0 inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#6852d6]/10 text-[#6852d6] text-xs md:text-sm font-bold tracking-widest uppercase font-sora">
          ✨ L'élite du trading africain
        </div>

        <h1 className="hero-element opacity-0 text-xl md:text-3xl lg:text-5xl font-bold text-center tracking-tight leading-[1.1] font-Archivo">
          Communauté
          <span className="ml-2 bg-gradient-to-r from-[#6852d6] via-[#8a76f0] to-[#6852d6] bg-[length:200%_auto] bg-clip-text text-transparent animate-gradient">
            RMI Class
          </span>
        </h1>

        <p className="hero-element opacity-0 text-base md:text-xl text-center font-sora text-gray-600 leading-relaxed max-w-2xl">
          Plus qu'un groupe, un véritable écosystème de traders engagés autour de l'analyse, 
          de la discipline et de la <span className="text-gray-900 font-semibold">performance collective</span>.
        </p>

        <div className="hero-element opacity-0 w-full flex justify-center items-center">
          <Button size="lg" className="group bg-[#6852d6] hover:bg-[#5841c5] w-full md:w-auto rounded-2xl px-10 py-4 h-auto text-xs md:text-lg font-bold font-sora transition-all shadow-xl shadow-[#6852d6]/25 hover:shadow-[#6852d6]/40 hover:-translate-y-1 active:scale-95">
            <Award className="award-icon w-6 h-6 mr-2 transition-transform group-hover:rotate-12" />
            Rejoignez le Top 1% maintenant
          </Button>
        </div>

        {/* Petit indicateur de preuve sociale factice ou réel */}
        <div className="hero-element opacity-0 flex flex-col sm:flex-row items-center gap-2 mt-4 text-sm text-gray-400 font-sora">
          <div className="flex -space-x-2">
            {instructors.map((instructor,i) => (
              <div key={i} className="w-8 h-8 rounded-full border-2 border-white bg-gray-200 overflow-hidden">
                <img
                    src={instructor.image}
                    alt={instructor.name}
                    className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                />
              </div>
            ))}
          </div>
          <span>+2,500 membres actifs</span>
        </div>
      </div>
    </section>
  );
};