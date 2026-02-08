import { useEffect, useRef } from "react";
import { Button } from "../../../../components/ui/button";
import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

export const CommunitySection = ({ features }: { features: { text: string }[] }) => {
  const containerRef = useRef<HTMLDivElement>(null);
  const featureRefs = useRef<HTMLDivElement[]>([]);

  const addFeatureRef = (el: HTMLDivElement) => {
    if (el && !featureRefs.current.includes(el)) {
      featureRefs.current.push(el);
    }
  };

  useEffect(() => {
    if (!containerRef.current) return;

    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: containerRef.current,
        start: "top 80%",
      },
    });

    // Animation du titre
    tl.from(containerRef.current.querySelector("h2"), {
      y: 50,
      opacity: 0,
      duration: 0.6,
      ease: "power3.out",
    });

    // Animation du paragraphe
    tl.from(containerRef.current.querySelector("p"), {
      y: 30,
      opacity: 0,
      duration: 0.5,
      ease: "power3.out",
    }, "-=0.3"); // chevauchement

    // Animation du bouton
    tl.from(containerRef.current.querySelector("button"), {
      scale: 0.8,
      opacity: 0,
      duration: 0.5,
      ease: "back.out(1.7)",
    }, "-=0.3");

    // Animation des features (petits points)
    tl.from(featureRefs.current, {
      y: 20,
      opacity: 0,
      stagger: 0.15,
      duration: 0.4,
      ease: "power3.out",
    }, "-=0.3");

  }, []);

  return (
    <div ref={containerRef} className="flex flex-col gap-6 w-full mt-8">
      <h2 className="text-2xl md:text-3xl lg:text-4xl font-bold text-center">
        Rejoignez une communauté qui transforme l'apprentissage du trading <br />
        <span className="text-[#6852d6]">en véritable compétence</span>
      </h2>
      <p className="text-center text-gray-700 [font-family:'Sora',Helvetica] text-sm md:text-base">
        Commencez dès aujourd'hui et accédez à des formations de qualité, un accompagnement professionnel et une communauté engagée.
      </p>

      <Button className="w-full md:w-auto md:mx-auto block bg-[#6852d6] hover:bg-[#5841c5] rounded-lg py-3 h-auto font-semibold text-white transition-all hover:shadow-lg">
        Créer votre compte maintenant
      </Button>

      <div className="flex flex-wrap items-center justify-center gap-4 md:gap-6 pt-2 w-full px-4">
        {features.map((feature, index) => (
          <div key={index} ref={addFeatureRef} className="inline-flex items-center gap-2">
            <div className="w-2 h-2 bg-green-500 rounded-full flex-shrink-0" />
            <span className="[font-family:'Sora',Helvetica] font-normal text-gray-700 text-xs md:text-sm leading-5 whitespace-nowrap">
              {feature.text}
            </span>
          </div>
        ))}
      </div>
    </div>
  );
};
