"use client";

import { useRef } from "react";
import { Card, CardContent } from "../../../components/ui/card";
import { Avatar, AvatarFallback, AvatarImage } from "../../../components/ui/avatar";
import { Star, Quote } from "lucide-react";
import gsap from "gsap";
import { useGSAP } from "@gsap/react";
import { ScrollTrigger } from "gsap/ScrollTrigger";

if (typeof window !== "undefined") {
  gsap.registerPlugin(ScrollTrigger);
}

const testimonials = [
  {
    stars: 5,
    quote: "La communauté m'a permis de progresser plus vite que prévu. Les analyses quotidiennes sont d'une précision redoutable pour mes entrées.",
    name: "Emmanuel K.",
    role: "Trader Indépendant",
    avatar: "/frame-17.png",
  },
  {
    stars: 5,
    quote: "Enfin une communauté sérieuse où l'on apprend vraiment. L'entourage est au rendez-vous et l'entraide est constante.",
    name: "Mariana B.",
    role: "Trader Forex",
    avatar: "/frame-17-1.png",
  },
  {
    stars: 5,
    quote: "Les Live Class sont un véritable game-changer. Pouvoir poser des questions en direct et voir l'exécution change absolument tout.",
    name: "Pascal T.",
    role: "Trader Indices",
    avatar: "/frame-17.png",
  },
];

export const CommunityTestimonialsSection = (): JSX.Element => {
  const container = useRef<HTMLDivElement>(null);

  useGSAP(() => {
    if (!container.current) return;

    gsap.set(".testimonial-card", { opacity: 0, y: 40 });

    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: container.current,
        start: "top 80%",
        toggleActions: "play none none none",
      },
    });

    tl.to(".testimonial-title", {
      opacity: 1,
      y: 0,
      duration: 0.8,
    })
    .to(".testimonial-card", {
      opacity: 1,
      y: 0,
      duration: 0.8,
      stagger: 0.2,
      ease: "power2.out",
    }, "-=0.4");

  }, { scope: container });

  return (
    <section ref={container} className="flex flex-col items-center px-4 md:px-6 lg:px-8 py-16 md:py-24 w-full bg-gray-50/50">
      <div className="max-w-6xl w-full">
        
        <div className="testimonial-title opacity-0 translate-y-4 text-center mb-16 md:mb-20">
          <h2 className="text-2xl md:text-4xl font-bold tracking-tight font-Archivo">
            Ils font partie de la <span className="text-[#6852d6]">communauté</span>
          </h2>
          <p className="mt-4 text-gray-500 font-sora">Découvrez les retours de nos membres actifs</p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          {testimonials.map((testimonial, index) => (
            <Card
              key={index}
              className="testimonial-card border-none rounded-[2rem] bg-white shadow-xl shadow-gray-200/40 transition-all duration-500 hover:-translate-y-2"
            >
              <CardContent className="p-8 md:p-10 flex flex-col h-full">
                {/* Icône Quote Stylisée */}
                <Quote className="w-10 h-10 text-[#6852d6]/10 mb-4" />

                <div className="flex gap-1 mb-6">
                  {Array(testimonial.stars)
                    .fill(0)
                    .map((_, i) => (
                      <Star
                        key={i}
                        className="w-4 h-4 fill-[#6852d6] text-[#6852d6]"
                      />
                    ))}
                </div>

                <blockquote className="text-base md:text-lg font-sora text-gray-700 italic leading-relaxed mb-8 flex-grow">
                  "{testimonial.quote}"
                </blockquote>

                <div className="flex items-center gap-4 pt-6 border-t border-gray-100">
                  <Avatar className="w-12 h-12 border-2 border-[#6852d6]/10">
                    <AvatarImage src={testimonial.avatar} alt={testimonial.name} />
                    <AvatarFallback className="bg-[#6852d6]/5 text-[#6852d6] font-bold">
                      {testimonial.name.charAt(0)}
                    </AvatarFallback>
                  </Avatar>
                  <div>
                    <p className="font-bold text-gray-900 font-Archivo">
                      {testimonial.name}
                    </p>
                    <p className="text-sm text-[#6852d6] font-medium font-sora">
                      {testimonial.role}
                    </p>
                  </div>
                </div>
              </CardContent>
            </Card>
          ))}
        </div>
      </div>
    </section>
  );
};