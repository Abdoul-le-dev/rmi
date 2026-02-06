"use client";

import { useRef } from "react";
import { Button } from "../../../components/ui/button";
import { Badge } from "../../../components/ui/badge";
import { CheckCircle, Crown, Rocket, Zap } from "lucide-react";
import { Card, CardContent } from "../../../components/ui/card";
import gsap from "gsap";
import { useGSAP } from "@gsap/react";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(useGSAP, ScrollTrigger);

const plans = [
  {
    name: "Standard",
    price: 535,
    icon: <Zap />,
    description: "Parfait pour les débutants prêts à se lancer dans le trading",
    features: [
      "+101 modules de cours détaillés",
      "+19 situations d'apprentissage",
      "11 examens pour évaluer votre niveau de compréhension (QCM)",
      "Accès aux outils de travail (Indicateurs - Templates)",
      "Accès aux outils de travail (Indicateurs - Templates)",
      "Accès à la communauté vip de la RMI CLASS (mois)",
      "Accès aux ateliers d'opportunités (3 mois)",
      "1 mois d'interaction avec la communauté (3 mois)",
      "Éligible aux sessions de coaching privé One-to-one",
      "Accès aux replays des sessions lives (3 mois)",
      "Interaction avec la communauté (3 mois)",
    ],
    highlighted: false,
  },
  {
    name: "Premium",
    price: 890,
    icon: <Crown />,
    description: "Le choix le plus populaire auprès des entrepreneurs",
    features: [
      "+101 modules de cours détaillés",
      "+19 situations d'apprentissage",
      "11 examens pour évaluer votre niveau de compréhension (QCM)",
      "Accès aux outils de travail (Indicateurs - Templates)",
      "Accès aux outils de travail (Indicateurs - Templates)",
      "Accès à la communauté vip de la RMI CLASS (mois)",
      "Accès aux ateliers d'opportunités (3 mois)",
      "6 mois d'interaction avec la communauté (3 mois)",
      "Éligible aux sessions de coaching privé One-to-one",
      "Accès aux replays des sessions lives (3 mois)",
      "Interaction avec la communauté (6 mois)",
    ],
    highlighted: true,
  },
  {
    name: "Elite",
    price: 1424,
    icon: <Rocket />,
    description: "Pour ceux qui veulent en position maximal et des résultats",
    features: [
      "+101 modules de cours détaillés",
      "+19 situations d'apprentissage",
      "11 examens pour évaluer votre niveau de compréhension (QCM)",
      "Accès aux outils de travail (Indicateurs - Templates)",
      "Accès aux outils de travail (Indicateurs - Templates)",
      "Accès à la communauté vip de la RMI CLASS (mois)",
      "Accès aux ateliers d'opportunités (3 mois)",
      "12 mois d'interaction avec la communauté (3 mois)",
      "Éligible aux sessions de coaching privé One-to-one",
      "Accès aux replays des sessions lives (12 mois)",
      "Interaction avec la communauté (12 mois)",
    ],
    highlighted: false,
  },
];

export const PricingSection = (): JSX.Element => {
  const container = useRef<HTMLDivElement>(null);

  useGSAP(() => {
    // On vérifie que le container existe
    if (!container.current) return;

    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: container.current,
        start: "top 85%", // Déclenchement un peu plus tôt pour plus de sécurité
        toggleActions: "play none none none",
      },
    });

    // On utilise set pour s'assurer que les éléments sont prêts
    gsap.set(".pricing-header, .pricing-card", { opacity: 0, y: 30 });

    tl.to(".pricing-header", {
      y: 0,
      opacity: 1,
      duration: 0.8,
      ease: "power3.out",
    })
      .to(".pricing-card", {
        y: 0,
        opacity: 1,
        duration: 0.8,
        stagger: 0.15,
        ease: "back.out(1.2)",
      }, "-=0.4");

    // Animation de pulsation du badge
    gsap.to(".popular-badge", {
      y: -5,
      repeat: -1,
      yoyo: true,
      duration: 1.5,
      ease: "sine.inOut"
    });

  }, { scope: container });

  return (
    <section ref={container} className="flex flex-col items-center px-4 md:px-6 lg:px-8 py-4 md:py-8 lg:py-12 w-full bg-gray-50/50">
      <div className="max-w-7xl w-full">

        {/* On ajoute opacity-0 par défaut pour éviter le flash de contenu */}
        <div className="pricing-header opacity-0 text-center mb-16 md:mb-20">
          <h2 className="text-2xl md:text-4xl font-bold mb-6 tracking-tight">
            Choisissez Votre <span className="text-[#6852d6]">Plan</span>
          </h2>
          <p className="max-w-2xl mx-auto text-base md:text-lg font-sora text-gray-600 leading-relaxed">
            Investissez dans votre avenir avec un programme adapté à vos ambitions.
          </p>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-100 md:gap-8 items-start">
          {plans.map((plan, index) => (
            <Card
              key={index}
              className={`pricing-card opacity-0 relative flex flex-col rounded-3xl border-2 transition-all duration-300 hover:shadow-2xl ${plan.highlighted
                ? "border-[#6852d6] shadow-xl md:scale-105 z-10 bg-white"
                : "border-gray-200 bg-white/80"
                }`}
            >
              {plan.highlighted && (
                <div className="absolute -top-5 left-1/2 -translate-x-1/2 popular-badge z-30">
                  <Badge className="bg-[#6852d6] text-white px-6 py-2 rounded-full font-sora shadow-lg border-none whitespace-nowrap">
                    ⭐ Plus Populaire
                  </Badge>
                </div>
              )}

              <CardContent className="p-8 md:p-10 flex flex-col gap-8 flex-grow">
                <div className="flex flex-col items-center text-center gap-4">
                  <div className="w-14 h-14 bg-[#6852d6]/10 text-[#6852d6] rounded-2xl flex justify-center items-center">
                    {plan.icon}
                  </div>
                  <h3 className="text-2xl font-bold font-Archivo text-gray-900">{plan.name}</h3>
                </div>

                <div className="text-center border-y border-gray-100 py-6">
                  <div className="flex items-center justify-center gap-1">
                    <span className="text-5xl font-bold text-gray-900">{plan.price}</span>
                    <span className="text-xl font-bold text-[#6852d6]">€</span>
                  </div>
                </div>

                <ul className="flex flex-col gap-4 flex-grow">
                  {plan.features.map((feature, fIndex) => (
                    <li key={fIndex} className="flex items-start gap-3">
                      <CheckCircle className="w-5 h-5 text-[#6852d6] shrink-0 mt-0.5" />
                      <span className="text-sm font-sora text-gray-600">{feature}</span>
                    </li>
                  ))}
                </ul>

                <Button size="lg" className={`w-full rounded-xl py-4 h-auto font-bold text-xs lg:text-md xl:text-lg ${plan.highlighted ? "bg-[#6852d6] text-white" : "bg-gray-900 text-white"
                  }`}>
                  Obtenir l'accès {plan.name}
                </Button>
              </CardContent>
            </Card>
          ))}
        </div>
      </div>
    </section>
  );
};