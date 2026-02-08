"use client";

import React, { useRef, useCallback } from "react";
import useEmblaCarousel from "embla-carousel-react";
import { Star, ChevronLeft, ChevronRight, CheckCircle2 } from "lucide-react";
import { Button } from "../../../../components/ui/button";
import { Card, CardContent } from "../../../../components/ui/card";
import gsap from "gsap";
import { useGSAP } from "@gsap/react";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { useNavigate } from "react-router-dom";

gsap.registerPlugin(useGSAP, ScrollTrigger);

const testimonials = [
  {
    quote: "RMI Class a transformé ma vision du trading. Les formations sont incroyablement complètes et l'accompagnement personnalisé fait toute la différence.",
    author: "Sarah A.",
    role: "Trader Indépendante",
    image: "https://images.pexels.com/photos/3807517/pexels-photo-3807517.jpeg?auto=compress&cs=tinysrgb&w=200&h=200&fit=crop",
    rating: 5,
  },
  {
    quote: "L'expertise de l'équipe m'a permis de passer de novice à confirmé en quelques mois. La communauté est vraiment motivante et pleine de bonne humeur.",
    author: "Jean M.",
    role: "Membre Elite",
    image: "https://images.pexels.com/photos/3931603/pexels-photo-3931603.jpeg?auto=compress&cs=tinysrgb&w=200&h=200&fit=crop",
    rating: 5,
  },
  {
    quote: "Enfin une plateforme francophone qui prend vraiment à cœur l'éducation financière. Un investissement qui vaut vraiment le coup pour son avenir.",
    author: "Marie D.",
    role: "Investisseuse",
    image: "https://images.pexels.com/photos/3945683/pexels-photo-3945683.jpeg?auto=compress&cs=tinysrgb&w=200&h=200&fit=crop",
    rating: 5,
  },
];

export const TestimonialsSection = (): JSX.Element => {
  const container = useRef<HTMLDivElement>(null);
  const navigate = useNavigate()

  // Configuration Embla
  const [emblaRef, emblaApi] = useEmblaCarousel({
    align: "start",
    loop: true,
    skipSnaps: false
  });

  const scrollPrev = useCallback(() => emblaApi && emblaApi.scrollPrev(), [emblaApi]);
  const scrollNext = useCallback(() => emblaApi && emblaApi.scrollNext(), [emblaApi]);

  useGSAP(() => {
    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: container.current,
        start: "top 80%",
        toggleActions: "play none none none",
      },
    });

    tl.from(".testi-header", { y: 30, opacity: 0, stagger: 0.2 })
      .from(".testi-carousel", { scale: 0.95, opacity: 0, duration: 0.8 }, "-=0.4")
      .from(".testi-footer", { y: 30, opacity: 0, duration: 0.8 }, "-=0.2");
  }, { scope: container });

  return (
    <section ref={container} className="px-4 md:px-6 lg:px-8 py-4 md:py-8 lg:py-12 bg-white w-full flex justify-center items-center text-center">
      <div className="w-full  max-w-5xl">

        {/* Header */}
        <div className="testi-header flex flex-col items-center mb-12 text-center">
          <h2 className="text-3xl md:text-4xl font-bold mb-4">
            Ils nous font <span className="text-[#6852d6]">confiance</span>
          </h2>
          <p className="text-gray-600 font-sora max-w-lg">
            Découvrez les retours de notre communauté et rejoignez des centaines de traders épanouis.
          </p>
        </div>

        {/* Carousel Embla */}
        <div className="testi-carousel relative group">
          <div className="overflow-hidden" ref={emblaRef}>
            <div className="flex -ml-4">
              {testimonials.map((t, i) => (
                <div key={i} className="flex-[0_0_100%] md:flex-[0_0_50%] pl-4 min-w-0">
                  <Card className="h-full border border-gray-100 shadow-sm bg-gray-50/50 rounded-2xl">
                    <CardContent className="p-8 flex flex-col h-full">
                      <div className="flex gap-1 mb-6">
                        {[...Array(t.rating)].map((_, i) => (
                          <Star key={i} className="w-4 h-4 fill-[#6852d6] text-[#6852d6]" />
                        ))}
                      </div>

                      <blockquote className="text-gray-700 font-sora text-base md:text-lg leading-relaxed mb-8 flex-grow">
                        "{t.quote}"
                      </blockquote>

                      <div className="flex items-center gap-4 pt-6 border-t border-gray-200">
                        <img src={t.image} alt={t.author} className="w-12 h-12 rounded-full object-cover ring-2 ring-[#6852d6]/10" />
                        <div>
                          <p className="font-bold text-gray-900 font-Archivo">{t.author}</p>
                          <p className="text-xs text-[#6852d6] font-medium">{t.role}</p>
                        </div>
                      </div>
                    </CardContent>
                  </Card>
                </div>
              ))}
            </div>
          </div>

          {/* Navigation Buttons */}
          <div className="flex justify-center gap-4 mt-8">
            <Button
              variant="outline"
              size="icon"
              onClick={scrollPrev}
              className="rounded-full border-[#6852d6] text-[#6852d6] hover:bg-[#6852d6] hover:text-white transition-all w-12 h-12"
            >
              <ChevronLeft className="w-6 h-6" />
            </Button>
            <Button
              variant="outline"
              size="icon"
              onClick={scrollNext}
              className="rounded-full border-[#6852d6] text-[#6852d6] hover:bg-[#6852d6] hover:text-white transition-all w-12 h-12"
            >
              <ChevronRight className="w-6 h-6" />
            </Button>
          </div>
        </div>

        {/* CTA */}
        <div className="testi-footer mt-20 text-center bg-gray-50 rounded-3xl px-4 py-8 md:p-8 lg:p-12 border border-gray-100">
          <h2 className="text-2xl md:text-3xl font-bold mb-6 leading-tight">
            Rejoignez une communauté qui transforme l'apprentissage <br className="hidden md:block" />
            <span className="text-[#6852d6]">en véritable compétence</span>
          </h2>

          <Button size="lg" onClick={() => navigate("/register")}
            className="w-full md:w-auto bg-[#6852d6] hover:bg-[#5841c5] rounded-xl px-10 py-4 h-auto text-sm md:text-lg font-bold shadow-lg shadow-[#6852d6]/20 transition-all hover:scale-105 mb-8">
            Créer votre compte maintenant
          </Button>

          <div className="flex flex-wrap justify-center gap-6">
            {["Inscription gratuite", "Accès immédiat", "Sans engagement"].map((text, i) => (
              <div key={i} className="flex items-center gap-2 text-gray-600 font-sora text-sm">
                <CheckCircle2 className="w-5 h-5 text-green-500" />
                {text}
              </div>
            ))}
          </div>
        </div>
      </div>
    </section>
  );
};