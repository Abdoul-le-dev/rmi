import React, { useRef } from "react";
import gsap from "gsap";
import { useGSAP } from "@gsap/react";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { Card, CardContent } from "../../../components/ui/card";
import { Badge } from "../../../components/ui/badge";
import { Linkedin, Twitter } from "lucide-react";

gsap.registerPlugin(useGSAP, ScrollTrigger);

const instructors = [
  {
    name: "Fiacre KPANOU",
    role: "Founder & Coach",
    badge: "Fondateur",
    image: "/fiacre.jpg",
    socials: ["linkedin", "twitter"],
  },
  {
    name: "Charbel Yayi",
    role: "Trader Indépendant",
    badge: undefined,
    image: "/charbel.jpg",
    socials: ["linkedin", "twitter"],
  },
  {
    name: "Hugues HOUNSA",
    role: "Trader Indépendant",
    badge: undefined,
    image: "/hugues.jpg",
    socials: ["linkedin", "twitter"],
  },
  {
    name: "Junice Y. HOUNGUE",
    role: "Coach",
    badge: "Coach certifié",
    image: "/junice.jpg",
    socials: ["linkedin", "twitter"],
  },
];

export const InstructorsGridSection = (): JSX.Element => {
  const container = useRef<HTMLDivElement>(null);

  useGSAP(() => {
    // Animation d'apparition des cartes en cascade
    gsap.from(".instructor-card", {
      scrollTrigger: {
        trigger: ".instructor-card",
        start: "top 85%",
        toggleActions: "play none none none",
      },
      y: 60,
      opacity: 0,
      duration: 1,
      stagger: 0.15, // Délai entre chaque carte
      ease: "power3.out",
    });
  }, { scope: container });

  return (
    <section ref={container} className="flex flex-col items-center px-4 md:px-6 lg:px-8 w-full pb-20">
      <div className="max-w-7xl w-full">
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
          {instructors.map((instructor, index) => (
            <Card
              key={index}
              className="instructor-card rounded-2xl overflow-hidden border-none shadow-lg hover:shadow-xl transition-shadow group"
            >
              <CardContent className="p-0 relative h-full min-h-[400px] flex flex-col">
                <div className="relative aspect-square overflow-hidden h-full bg-gray-200">
                  <img
                    src={instructor.image}
                    alt={instructor.name}
                    className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                  />
                  <div className="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-80" />

                  {instructor.badge && (
                    <Badge className="absolute top-4 right-4 bg-[#6852d6] text-white px-3 py-1 rounded-full font-[Sora] text-xs md:text-sm">
                      {instructor.badge}
                    </Badge>
                  )}

                  <div className="absolute bottom-4 left-0 right-0 px-4 flex flex-col gap-2">
                    <h3 className="text-lg md:text-xl font-bold text-white [font-family:'Archivo',Helvetica]">
                      {instructor.name}
                    </h3>
                    <p className="text-xs md:text-sm text-gray-200 [font-family:'Sora',Helvetica]">
                      {instructor.role}
                    </p>
                  </div>

                  <div className="absolute bottom-4 right-4 flex gap-2">
                    {instructor.socials.map((social, i) => (
                      <a
                        key={i}
                        href="#"
                        className="w-9 h-9 md:w-10 md:h-10 rounded-full bg-[#6852d6] text-white flex items-center justify-center hover:bg-[#5841c5] hover:scale-110 transition-all"
                      >
                        <span className="text-sm">
                          {social === "linkedin" ? <Linkedin size={18} /> : <Twitter size={18} />}
                        </span>
                      </a>
                    ))}
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