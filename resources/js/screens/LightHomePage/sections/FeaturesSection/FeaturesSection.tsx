"use client";

import { useRef } from "react";
import gsap from "gsap";
import { useGSAP } from "@gsap/react";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { ArrowRightCircleIcon } from "lucide-react";
import { Badge } from "../../../../components/ui/badge";
import { Button } from "../../../../components/ui/button";
import { Card, CardContent } from "../../../../components/ui/card";
import { useNavigate } from "react-router-dom";

gsap.registerPlugin(useGSAP, ScrollTrigger);

const formationCards = [
  {
    icon: "/background-5.svg",
    category: "Formation d'introduction",
    title: "Initiation au Trading",
    description: "Idéale pour les débutants souhaitant comprendre les bases du trading et des marchés financiers.",
    features: ["Bases du trading", "Vocabulaire essentiel", "Types de marchés"],
    buttonText: "Obtenir l'accès exclusif",
    recommended: false,
  },
  {
    icon: "/background.svg",
    category: "Live Class & Analyses",
    title: "RMI Class Community",
    description: "Accédez aux sessions live, analyses Forex & indices, et rejoignez une communauté de traders actifs.",
    features: ["Live Class", "Communauté VIP", "Certification"],
    buttonText: "Rejoignez le Club des Elites",
    recommended: true,
  },
];

export const FeaturesSection = (): JSX.Element => {
  const container = useRef<HTMLDivElement>(null);
  const navigate = useNavigate()

  useGSAP(() => {
    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: container.current,
        start: "top 75%",
        toggleActions: "play none none none",
      },
    });

    // 1. Animation du Header
    tl.from(".features-header", {
      y: 30,
      opacity: 0,
      duration: 0.8,
      stagger: 0.2,
      ease: "power3.out"
    });

    // 2. Animation des Cartes (Scale + Y)
    tl.from(".formation-card", {
      scale: 0.9,
      y: 40,
      opacity: 0,
      duration: 1,
      stagger: 0.2,
      ease: "power4.out",
      clearProps: "all"
    }, "-=0.4");

    // 3. Animation du Badge Recommandé (Petit rebond après l'entrée)
    tl.from(".recommended-badge", {
      scale: 0,
      opacity: 0,
      duration: 0.6,
      ease: "back.out(1.7)",
    }, "-=0.2");

  }, { scope: container });

  return (
    <section ref={container} className="flex flex-col items-center px-4 md:px-6 lg:px-8 py-4 md:py-8 lg:py-12 w-full overflow-hidden">
      <div className="flex flex-col max-w-5xl items-center gap-8 md:gap-4 lg:gap-8 w-full">

        {/* Header */}
        <div className="flex flex-col items-center gap-4 w-full">
          <h2 className="features-header text-2xl md:text-3xl lg:text-4xl font-bold text-center">
            Nos <span className="text-[#6852d6]">Formations</span>
          </h2>
          <p className="features-header text-center text-gray-600 [font-family:'Sora',Helvetica] text-sm md:text-base">
            Choisissez le parcours adapté à votre niveau et vos objectifs
          </p>
        </div>

        {/* Cards Grid */}
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8 w-full">
          {formationCards.map((card, index) => (
            <Card
              key={index}
              className="formation-card flex flex-col bg-white rounded-2xl overflow-hidden border border-gray-200 relative hover:shadow-2xl transition-all duration-500 hover:-translate-y-1"
            >
              {card.recommended && (
                <Badge className="recommended-badge absolute top-4 right-4 px-3 py-1.5 rounded-full bg-[#6852d6] text-white text-xs md:text-sm font-semibold z-10">
                  Recommandé
                </Badge>
              )}

              <CardContent className="p-6 md:p-8 flex flex-col gap-4 md:gap-6 h-full">
                <img className="w-12 h-12 md:w-16 md:h-16" alt="Icon" src={card.icon} />

                <div className="flex flex-col gap-2">
                  <p className="text-gray-500 text-xs md:text-sm [font-family:'Sora',Helvetica]">
                    {card.category}
                  </p>
                  <h3 className="text-[#6852d6] font-bold text-xl md:text-2xl [font-family:'Archivo',Helvetica]">
                    {card.title}
                  </h3>
                </div>

                <p className="text-gray-600 text-sm md:text-base [font-family:'Sora',Helvetica] leading-relaxed flex-grow">
                  {card.description}
                </p>

                <ul className="flex flex-col gap-2 my-2">
                  {card.features.map((feature, featureIndex) => (
                    <li key={featureIndex} className="flex items-center gap-2">
                      <div className="w-1.5 h-1.5 rounded-full bg-[#6852d6] flex-shrink-0" />
                      <span className="text-gray-700 text-xs md:text-sm [font-family:'Sora',Helvetica]">
                        {feature}
                      </span>
                    </li>
                  ))}
                </ul>

                <Button onClick={() => navigate("/devenir-trader-pro")}
                  className="w-full bg-[#6852d6] hover:bg-[#5841c5] rounded-lg py-4 h-auto font-semibold text-white flex items-center justify-center gap-2 transition-all group">
                  <ArrowRightCircleIcon className="w-5 h-5 transition-transform group-hover:translate-x-1" />
                  <span className="text-sm md:text-base">{card.buttonText}</span>
                </Button>
              </CardContent>
            </Card>
          ))}
        </div>
      </div>
    </section>
  );
};