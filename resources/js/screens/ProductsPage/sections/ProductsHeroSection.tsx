import { useEffect, useRef } from "react";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

export const ProductsHeroSection = (): JSX.Element => {
  const sectionRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    if (!sectionRef.current) return;

    const ctx = gsap.context(() => {
      gsap.fromTo(
        ".hero-item",
        {
          opacity: 0,
          y: 50,
        },
        {
          opacity: 1,
          y: 0,
          duration: 1,
          ease: "power3.out",
          stagger: 0.2,
          scrollTrigger: {
            trigger: sectionRef.current,
            start: "top 80%",
          },
        }
      );
    }, sectionRef);

    return () => ctx.revert();
  }, []);

  return (
    <section
      ref={sectionRef}
      className="flex flex-col items-center px-4 md:px-6 lg:px-8 py-4 md:py-8 lg:py-12 w-full"
    >
      <div className="flex flex-col items-center gap-6 md:gap-8 max-w-4xl">
        {/* Title */}
        <h1 className="hero-item text-4xl md:text-5xl lg:text-6xl font-bold text-center">
          Nos Produits &{" "}
          <span className="bg-[linear-gradient(90deg,rgba(104,82,214,1)_0%)] bg-clip-text text-transparent">
            Formations
          </span>
        </h1>

        {/* Description */}
        <p className="hero-item text-base md:text-lg text-center [font-family:'Sora',Helvetica] text-app-shark leading-8">
          Accédez à des formations et ressources conçues pour accompagner chaque
          étape de votre parcours en trading.
        </p>
      </div>
    </section>
  );
};
