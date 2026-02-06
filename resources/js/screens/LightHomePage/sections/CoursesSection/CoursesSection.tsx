import { useRef } from "react";
import gsap from "gsap";
import { useGSAP } from "@gsap/react";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { Card, CardContent } from "../../../../components/ui/card";

gsap.registerPlugin(useGSAP, ScrollTrigger);

const benefitsData = [
  {
    icon: "/background-7.svg",
    title: "Apprentissage structuré et progressif",
    description:
      "Un parcours pédagogique clair, du débutant au trader confirmé. Chaque module est conçu pour construire vos compétences étape par étape.",
  },
  {
    icon: "/background-1.svg",
    title: "Encadrement par des traders expérimentés",
    description:
      "Bénéficiez de l'expertise de professionnels ayant fait leurs preuves. Nos instructeurs partagent leurs stratégies et retours d'expérience.",
  },
  {
    icon: "/background-6.svg",
    title: "Live Class et analyses en temps réel",
    description:
      "Participez à des sessions live pour comprendre les marchés en action. Apprenez directement des mouvements réels avec nos experts.",
  },
  {
    icon: "/background-2.svg",
    title: "Psychologie du trader & discipline mentale",
    description:
      "Maîtrisez l'aspect mental essentiel au succès en trading. Gérez vos émotions et développez une discipline inébranlable.",
  },
  {
    icon: "/background-3.svg",
    title: "Accès à vie aux contenus",
    description:
      "Revenez sur les formations autant que vous le souhaitez. Mises à jour gratuites et accès permanent à tous les matériels.",
  },
  {
    icon: "/background-4.svg",
    title: "Communauté active & privée",
    description:
      "Échangez avec d'autres traders motivés et entraidez-vous. Un réseau solidaire pour progresser ensemble.",
  },
];

export const CoursesSection = (): JSX.Element => {
  const container = useRef<HTMLDivElement>(null);

  useGSAP(() => {
    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: container.current,
        start: "top 80%",
        toggleActions: "play none none none",
      },
    });

    // 1. Animation du Header (Titre + Description)
    tl.from(".benefit-header", {
      y: 30,
      opacity: 0,
      duration: 0.8,
      stagger: 0.2,
      ease: "power3.out"
    });

    // 2. Animation des Cartes
    tl.from(".benefit-card", {
      y: 50,
      opacity: 0,
      duration: 0.8,
      stagger: 0.1,
      ease: "power2.out",
      clearProps: "all" // Permet aux hovers CSS de fonctionner après l'anim
    }, "-=0.4");

  }, { scope: container });

  return (
    <section
      ref={container}
      className="flex flex-col items-center gap-4 md:gap-8 lg:gap-16 px-4 md:px-6 lg:px-8 py-4 md:pt-8 lg:pt-12 w-full overflow-hidden"
    >
      {/* Header */}
      <div className="flex flex-col items-center gap-4 max-w-3xl w-full">
        <h2 className="benefit-header text-2xl md:text-3xl lg:text-4xl font-bold text-center">
          Pourquoi rejoindre <span className="text-[#6852d6]">RMI Class</span> ?
        </h2>
        <p className="benefit-header text-center text-gray-600 [font-family:'Sora',Helvetica] text-sm md:text-base">
          Découvrez ce qui fait de notre plateforme la référence pour les traders francophones
        </p>
      </div>

      {/* Cards */}
      <div className="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 w-full max-w-6xl px-4">
        {benefitsData.map((benefit, index) => (
          <Card
            key={index}
            className="benefit-card bg-white border border-gray-200 transition-all duration-300
                       hover:-translate-y-2 hover:shadow-xl hover:border-[#6852d6]/40"
          >
            <CardContent className="p-6 md:p-8 flex flex-col gap-4 md:gap-6">
              <img
                className="w-12 h-12 md:w-14 md:h-14"
                alt={benefit.title}
                src={benefit.icon}
              />
              <h3 className="[font-family:'Archivo',Helvetica] font-bold text-lg md:text-xl text-gray-900">
                {benefit.title}
              </h3>
              <p className="[font-family:'Sora',Helvetica] text-sm md:text-base text-gray-600 leading-relaxed">
                {benefit.description}
              </p>
            </CardContent>
          </Card>
        ))}
      </div>
    </section>
  );
};