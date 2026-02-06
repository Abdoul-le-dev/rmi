"use client";

import { useRef } from "react";
import { Card, CardContent } from "../../../components/ui/card";
import { 
  TrendingUp, BarChart3, Video, Brain, 
  Video as VideoIcon, Zap, MessageCircle, Lock 
} from "lucide-react";
import gsap from "gsap";
import { useGSAP } from "@gsap/react";
import { ScrollTrigger } from "gsap/ScrollTrigger";

if (typeof window !== "undefined") {
  gsap.registerPlugin(ScrollTrigger);
}

const benefits = [
  { 
    icon: TrendingUp, 
    title: "Analyse Forex", 
    description: "Décryptage quotidien des paires majeures pour anticiper les mouvements de marché." 
  },
  { 
    icon: BarChart3, 
    title: "Analyse Indices", 
    description: "Stratégies précises sur le Nasdaq, DAX et US30 pour maximiser votre volatilité." 
  },
  { 
    icon: Video, 
    title: "Live Class", 
    description: "Sessions de trading en direct pour observer la psychologie d'un pro en temps réel." 
  },
  { 
    icon: Brain, 
    title: "Psychologie", 
    description: "Maîtrisez vos émotions et développez le mindset d'acier nécessaire pour gagner." 
  },
  { 
    icon: VideoIcon, 
    title: "Ressources vidéo", 
    description: "Accès illimité à une bibliothèque de cours exclusifs pour réviser à votre rythme." 
  },
  { 
    icon: Zap, 
    title: "Setups & Signaux", 
    description: "Recevez nos meilleures opportunités filtrées avec des points d'entrée précis." 
  },
  { 
    icon: MessageCircle, 
    title: "Interactions", 
    description: "Échangez avec des centaines de traders passionnés pour progresser ensemble." 
  },
  { 
    icon: Lock, 
    title: "Séances privées", 
    description: "Coaching One-to-One pour corriger vos erreurs et propulser vos résultats." 
  },
];

export const CommunityBenefitsSection = (): JSX.Element => {
  const container = useRef<HTMLDivElement>(null);

  useGSAP(() => {
    if (!container.current) return;

    gsap.set(".benefit-card-anim", { opacity: 0, scale: 0.95, y: 30 });

    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: container.current,
        start: "top 80%",
        toggleActions: "play none none none",
      },
    });

    tl.to(".benefit-header", { opacity: 1, y: 0, duration: 0.8 })
      .to(".benefit-card-anim", {
        opacity: 1,
        scale: 1,
        y: 0,
        duration: 0.5,
        stagger: 0.1,
        ease: "power2.out"
      }, "-=0.4");

  }, { scope: container });

  return (
    <section 
      ref={container} 
      className="flex flex-col items-center px-4 md:px-6 lg:px-8 py-16 md:py-24 w-full bg-gray-50/50 overflow-hidden"
    >
      <div className="max-w-7xl w-full">
        
        <div className="benefit-header opacity-0 translate-y-4 text-center mb-16 md:mb-20">
          <h2 className="text-2xl md:text-4xl font-bold tracking-tight font-Archivo">
            Ce que vous obtenez en rejoignant la <br className="hidden md:block" />
            <span className="text-[#6852d6]">communauté RMI Class</span>
          </h2>
          <p className="mt-4 text-gray-500 font-sora max-w-2xl mx-auto">
            Un arsenal complet d'outils et un accompagnement d'élite pour transformer votre trading.
          </p>
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          {benefits.map((benefit, index) => {
            const IconComponent = benefit.icon;
            return (
              <Card
                key={index}
                className="benefit-card-anim opacity-0 group relative border-none rounded-[2rem] bg-white shadow-sm hover:shadow-2xl transition-all duration-500 cursor-pointer"
              >
                <CardContent className="p-8 flex flex-col items-center text-center h-full">
                  {/* Icône */}
                  <div className="mb-6 w-14 h-14 bg-[#6852d6]/10 rounded-2xl flex items-center justify-center group-hover:bg-[#6852d6] transition-colors duration-500">
                    <IconComponent className="w-6 h-6 text-[#6852d6] group-hover:text-white transition-transform duration-500 group-hover:rotate-[10deg]" />
                  </div>
                  
                  {/* Titre */}
                  <h3 className="text-lg font-bold font-Archivo text-gray-900 mb-3">
                    {benefit.title}
                  </h3>
                  
                  {/* Description - Ajoutée ici */}
                  <p className="text-sm font-sora text-gray-500 leading-relaxed">
                    {benefit.description}
                  </p>

                  {/* Décoration de bas de carte */}
                  <div className="mt-auto pt-6">
                    <div className="w-6 h-1 bg-gray-100 group-hover:w-12 group-hover:bg-[#6852d6] transition-all duration-500 rounded-full" />
                  </div>
                </CardContent>
              </Card>
            );
          })}
        </div>
      </div>
    </section>
  );
};