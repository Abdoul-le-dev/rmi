import React, { useRef } from "react";
import gsap from "gsap";
import { useGSAP } from "@gsap/react";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { Button } from "../../../components/ui/button";
import { ArrowRight } from "lucide-react";
import { useNavigate } from "react-router-dom";

gsap.registerPlugin(useGSAP, ScrollTrigger);

export const ReadySection = (): JSX.Element => {
  const container = useRef<HTMLDivElement>(null);
  const navigate = useNavigate()


  useGSAP(() => {
    // Timeline principale
    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: container.current,
        start: "top 85%",
        toggleActions: "play none none none",
      },
    });

    // On anime les éléments un par un
    tl.from(".ready-h2", { y: 30, opacity: 0, duration: 0.6 })
      .from(".ready-p", { y: 20, opacity: 0, duration: 0.6 }, "-=0.3")
      .from(".cta-button", {
        y: 20,
        opacity: 0,
        duration: 0.6,
        clearProps: "all" // Très important : nettoie les styles GSAP après l'anim pour laisser le hover CSS reprendre la main
      }, "-=0.3")
      .from(".ready-small", { opacity: 0, duration: 0.4 }, "-=0.2");

    // Animation de pulsation continue sur le bouton (optionnelle, mais sympa)
    gsap.to(".cta-button", {
      scale: 1.03,
      duration: 1.2,
      repeat: -1,
      yoyo: true,
      ease: "sine.inOut",
      delay: 1.5 // Attend que l'intro soit finie
    });
  }, { scope: container });

  return (
    <section ref={container} className="flex flex-col items-center px-4 md:px-6 lg:px-8 py-4 md:py-8 lg:py-12 w-full">
      <div className="flex flex-col items-center gap-4 max-w-3xl text-center w-full">
        <h2 className="ready-h2 text-2xl md:text-4xl font-bold">
          Prêt à démarrer votre parcours <span className="text-[#6852d6]">trading</span> ?
        </h2>

        <p className="ready-p text-base md:text-lg [font-family:'Sora',Helvetica] text-app-shark leading-8">
          Construisez des bases solides avec notre formation d'initiation gratuite.
        </p>

        <Button onClick={() => navigate("/devenir-trader-pro")} className="cta-button bg-[#6852d6] hover:bg-[#5841c5] rounded-lg px-8 py-3 h-auto w-full md:w-auto flex items-center gap-2 font-semibold [font-family:'Sora',Helvetica]">
          Démarrer votre initiation maintenant
          <ArrowRight className="w-5 h-5" />
        </Button>

        <p className="ready-small text-xs md:text-sm [font-family:'Sora',Helvetica] text-gray-600">
          Formation 100% gratuite • Accès immédiat
        </p>
      </div>
    </section>
  );
};