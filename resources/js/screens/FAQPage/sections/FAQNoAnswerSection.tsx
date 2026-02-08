"use client";

import { useRef } from "react";
import { Button } from "../../../components/ui/button";
import { Link } from "react-router-dom";
import { MessageCircle, Users } from "lucide-react";
import gsap from "gsap";
import { useGSAP } from "@gsap/react";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(useGSAP, ScrollTrigger);

export const FAQNoAnswerSection = (): JSX.Element => {
  const container = useRef<HTMLDivElement>(null);

  useGSAP(() => {
    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: container.current,
        start: "top 85%",
        toggleActions: "play none none none",
      },
    });

    // 1. Entrée du texte
    tl.from(".no-answer-content", {
      y: 30,
      opacity: 0,
      duration: 0.8,
      stagger: 0.2,
      ease: "power3.out",
    })
    // 2. Apparition des boutons avec un léger rebond
    .from(".no-answer-btns", {
      scale: 0.9,
      opacity: 0,
      duration: 0.6,
      stagger: 0.15,
      ease: "back.out(1.7)",
      clearProps: "all"
    }, "-=0.4");

    // 3. Petit effet de flottement continu sur le bouton principal
    gsap.to(".floating-btn", {
      y: -5,
      duration: 2,
      repeat: -1,
      yoyo: true,
      ease: "sine.inOut"
    });

  }, { scope: container });

  return (
    <section 
      ref={container} 
      className="flex flex-col items-center px-4 md:px-6 lg:px-8 py-16 md:py-24 w-full bg-white"
    >
      <div className="flex flex-col items-center gap-8 max-w-2xl text-center">
        <div className="space-y-4">
          <h2 className="no-answer-content text-2xl md:text-4xl font-bold font-Archivo text-gray-900">
            Vous n'avez pas trouvé votre réponse ?
          </h2>

          <p className="no-answer-content text-base md:text-lg font-sora text-gray-600 leading-relaxed">
            Notre équipe d'experts est à votre disposition pour vous guider. 
            Posez-nous vos questions directement via le support ou rejoignez nos membres.
          </p>
        </div>

        <div className="flex flex-col sm:flex-row gap-4 w-full sm:w-auto mt-4">
          <Link to="/about" className="no-answer-btns w-full sm:w-auto">
            <Button className="floating-btn w-full sm:w-auto bg-[#6852d6] hover:bg-[#5841c5] rounded-xl px-10 py-4 h-auto font-bold text-white shadow-lg shadow-[#6852d6]/20 transition-all flex items-center justify-center gap-2">
              <MessageCircle className="w-5 h-5" />
              Contacter le support
            </Button>
          </Link>
          
          <Link to="/communaute" className="no-answer-btns w-full sm:w-auto">
            <Button
              variant="outline"
              className="w-full sm:w-auto border-2 border-gray-200 hover:border-[#6852d6] hover:text-[#6852d6] rounded-xl px-10 py-4 h-auto font-bold font-sora transition-all flex items-center justify-center gap-2"
            >
              <Users className="w-5 h-5" />
              Rejoindre la communauté
            </Button>
          </Link>
        </div>
      </div>
    </section>
  );
};