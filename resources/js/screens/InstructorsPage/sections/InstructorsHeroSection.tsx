import React, { useRef } from "react";
import gsap from "gsap";
import { useGSAP } from "@gsap/react";

export const InstructorsHeroSection = (): JSX.Element => {
  const container = useRef<HTMLDivElement>(null);

  useGSAP(() => {
    const tl = gsap.timeline({ defaults: { ease: "power4.out" } });

    // Animation du titre et du texte
    tl.from(".animate-title", {
      y: 50,
      opacity: 0,
      duration: 1,
    })
    .from(".animate-text", {
      y: 30,
      opacity: 0,
      duration: 0.8,
    }, "-=0.6") // Commence un peu avant la fin de l'animation précédente
    
    // Petit effet d'éclat sur le gradient du mot "Instructeurs"
    .to(".instructor-gradient", {
      backgroundPosition: "200% center",
      duration: 2,
      ease: "none",
      repeat: -1
    }, "-=0.2");

  }, { scope: container });

  return (
    <section ref={container} className="flex flex-col items-center px-4 md:px-6 lg:px-8 py-4 md:py-8 lg:py-12 w-full">
      <div className="flex flex-col items-center gap-6 md:gap-8 max-w-4xl">
        <h1 className="animate-title text-2xl md:text-4xl lg:text-6xl font-bold text-center">
          Nos{" "}
          <span className="instructor-gradient bg-[linear-gradient(90deg,rgba(104,82,214,1)_0%,rgba(166,127,224,1)_50%,rgba(104,82,214,1)_100%)] bg-[length:200%_auto] [-webkit-background-clip:text] bg-clip-text [-webkit-text-fill-color:transparent] [text-fill-color:transparent]">
            Instructeurs
          </span>
        </h1>

        <p className="animate-text text-base md:text-lg text-center [font-family:'Sora',Helvetica] text-app-shark leading-8">
          Des professionnels du trading qui vous accompagnent à chaque étape <br /> de votre progression.
        </p>
      </div>
    </section>
  );
};