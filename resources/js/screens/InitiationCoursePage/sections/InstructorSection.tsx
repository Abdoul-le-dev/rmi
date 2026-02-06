import React, { useRef } from "react";
import gsap from "gsap";
import { useGSAP } from "@gsap/react";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { Award, Users, DollarSign, Star } from "lucide-react";

gsap.registerPlugin(useGSAP, ScrollTrigger);

export const InstructorSection = (): JSX.Element => {
  const container = useRef<HTMLDivElement>(null);

  useGSAP(() => {
    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: container.current,
        start: "top 75%",
        toggleActions: "play none none none",
      },
    });

    // 1. Animation de la partie Image et Badges flottants
    tl.from(".instructor-image-box", {
      x: -50,
      opacity: 0,
      duration: 1,
      ease: "power3.out",
    })
      .from(".floating-stat", {
        scale: 0,
        opacity: 0,
        duration: 0.8,
        stagger: 0.2,
        ease: "back.out(1.7)",
      }, "-=0.6")

      // 2. Animation du contenu texte à droite
      .from(".instructor-text-content > *", {
        x: 50,
        opacity: 0,
        duration: 0.8,
        stagger: 0.15,
        ease: "power3.out",
      }, "-=1")

      // 3. Animation des petits items de caractéristiques
      .from(".feature-item", {
        y: 20,
        opacity: 0,
        duration: 0.5,
        stagger: 0.1,
        ease: "power2.out",
      }, "-=0.5");

    // 4. Animation de flottement continu (Idle) pour les stats
    gsap.to(".floating-stat", {
      y: -10,
      duration: 2,
      repeat: -1,
      yoyo: true,
      ease: "sine.inOut",
      stagger: 0.3
    });
  }, { scope: container });

  return (
    <section ref={container} className="relative flex flex-col items-center px-4 md:px-6 lg:px-8 py-4 md:py-8 lg:py-12 w-full bg-gray-50 overflow-hidden">

      {/* Subtle background glow */}
      <div className="absolute top-1/2 left-0 w-96 h-96 bg-[#6852d6]/10 rounded-full blur-[150px] -translate-y-1/2" />

      <div className="container px-4 md:px-6 relative z-10 max-w-7xl">
        <div className="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">

          {/* Bloc Image / Gauche */}
          <div className="instructor-image-box relative">
            <div className="relative aspect-square max-w-md mx-auto lg:mx-0 bg-white shadow-lg shadow-[#6852d6]/20 flex flex-col justify-center items-center text-center rounded-2xl border border-[#6852d6]">
              <div className="flex flex-col justify-center items-center text-center gap-4">
                <div className="w-32 h-32 rounded-full flex justify-center items-center text-center border-2 border-[#6852d6] overflow-hidden">
                  <img src="/instructor.png" className="h-full object-cover" alt="Fiacre KPANOU" loading="lazy" />
                </div>
                <div className="flex flex-col justify-center items-center text-center gap-1">
                  <strong className="text-rmi-colors-stylesshark">Fiacre KPANOU</strong>
                  <span className="text-gray-500">Votre Instructeur</span>
                </div>
              </div>

              {/* Floating stats - Badge Droite */}
              <div className="flex floating-stat absolute -right-4 -top-12 md:top-8 bg-white border border-[#6852d6] rounded-xl p-4 shadow-lg">
                <div className="flex items-center gap-3">
                  <div className="md:w-10 md:h-10 rounded-lg bg-[#6852d6]/10 flex items-center justify-center">
                    <DollarSign className="w-5 h-5 text-[#6852d6]" />
                  </div>
                  <div>
                    <p className="font-semibold">$2M+</p>
                    <p className="text-xs text-muted-foreground">Revenue Generated</p>
                  </div>
                </div>
              </div>

              {/* Floating stats - Badge Gauche */}
              <div className="flex floating-stat absolute -left-4 -bottom-12 md:bottom-8 bg-white border border-[#6852d6] rounded-xl p-4 shadow-lg">
                <div className="flex items-center gap-3">
                  <div className="w-10 h-10 rounded-lg bg-[#6852d6]/10 flex items-center justify-center">
                    <Users className="w-5 h-5 text-[#6852d6]" />
                  </div>
                  <div>
                    <p className="font-semibold">500+</p>
                    <p className="text-xs text-muted-foreground">Students Trained</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          {/* Bloc Texte / Droite */}
          <div className="instructor-text-content flex flex-col">
            <h2 className="text-2xl md:text-4xl font-bold mb-6 [font-family:'Archivo',Helvetica]">
              Votre instructeur <span className="text-[#6852d6]">Fiacre D. KPANOU.</span>
            </h2>
            <p className="text-lg mb-8 [font-family:'Sora',Helvetica] text-gray-700 leading-relaxed">
              Fiacre D. KPANOU est votre mentor pour naviguer avec confiance sur les marchés financiers. Avec une vision pragmatique des marchés, Fiacre transforme la théorie complexe en stratégies d'investissement applicables immédiatement.
            </p>

            <div className="grid sm:grid-cols-2 gap-4 mb-8">
              {[
                { icon: Award, label: "Certified Expert" },
                { icon: Star, label: "5+ Years Experience" },
                { icon: DollarSign, label: "$2M+ in Revenue" },
                { icon: Users, label: "500+ Students Mentored" },
              ].map((item, index) => (
                <div key={index} className="feature-item flex items-center gap-3 p-4 rounded-xl bg-white border border-gray-100 shadow-sm">
                  <item.icon className="w-5 h-5 text-[#6852d6]" />
                  <span className="font-medium text-sm md:text-base">{item.label}</span>
                </div>
              ))}
            </div>

            <blockquote className="border-l-4 border-[#6852d6] pl-6 italic text-gray-600 [font-family:'Sora',Helvetica]">
              "Ma mission est d'aider les entrepreneurs en herbe à créer des entreprises durables qui leur apportent liberté financière et flexibilité. Ce cours contient tout ce que j'aurais aimé savoir quand j'ai commencé."
            </blockquote>
          </div>
        </div>
      </div>
    </section>
  );
};