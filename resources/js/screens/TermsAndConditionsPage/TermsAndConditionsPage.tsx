import { useEffect, useRef } from "react";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

export const TermsAndConditionsPage = (): JSX.Element => {
  const containerRef = useRef<HTMLDivElement>(null);
  const headerRef = useRef<HTMLDivElement>(null);

  const sections = [
    {
      title: "Introduction",
      content:
        "Les conditions d'utilisation de ce site sont divisées en plusieurs sections. Avant de les accepter, veuillez lire attentivement les sections ci-dessous, ainsi que les liens fournis. Elles constituent les Conditions Générales et la Politique de Confidentialité de la plateforme RMI Class.",
    },
    {
      title: "Qui sommes-nous ?",
      content:
        "La RMI class est une plateforme d'éducation et d'accompagnement en ligne dans le domaine du Trading sur les marchés financiers.",
    },
    {
      title: "Nos services",
      content: "RMI Class permet aux utilisateurs d'accéder à plusieurs services, incluant :",
      items: [
        "L'achat de cours de trading : Accédez à une variété de cours préenregistrés pour apprendre les bases et les techniques avancées du trading.",
        "Sessions de formation en direct : Participez à des classes en direct avec des experts pour approfondir vos connaissances et poser vos questions en temps réel.",
        "Coaching privé : Réservez des séances de coaching personnalisées pour bénéficier de conseils sur mesure.",
        "Achat d'outils pédagogiques : Offrez-vous les meilleurs outils techniques qui propulseront votre trading à un haut niveau tel que notre livre et le guide de performance du trader.",
      ],
    },
    {
      title: "Conditions Générales",
      subsections: [
        {
          subtitle: "Accessibilité et Application",
          content:
            "Les CG sont disponibles sur la plateforme et doivent être acceptées lors de la création de compte et pour toute utilisation des services.",
        },
        {
          subtitle: "Langue",
          content:
            "En cas de divergence ou de litige, la version française des Conditions Générales d'utilisation prévaudra.",
        },
        {
          subtitle: "Absence de Services d'Investissement",
          content:
            "RMI Class ne propose aucun service d'investissement ni conseil financier.",
        },
        {
          subtitle: "Fraudes et Répression",
          suspiciousItems: [
            "Partage d'accès non autorisé",
            "Multiplication des comptes",
            "Fraude ou tentative de fraude",
            "Violation des droits d'auteur",
          ],
        },
      ],
    },
  ];

  useEffect(() => {
    const ctx = gsap.context(() => {
      // HERO
      gsap.fromTo(
        headerRef.current?.children as HTMLCollection,
        { opacity: 0, y: 40 },
        {
          opacity: 1,
          y: 0,
          duration: 1,
          ease: "power3.out",
          stagger: 0.15,
        }
      );

      // SECTIONS
      gsap.utils.toArray<HTMLElement>(".terms-section").forEach((section) => {
        gsap.fromTo(
          section,
          { opacity: 0, y: 60 },
          {
            opacity: 1,
            y: 0,
            duration: 1,
            ease: "power3.out",
            scrollTrigger: {
              trigger: section,
              start: "top 85%",
            },
          }
        );
      });

      // CARDS
      gsap.fromTo(
        ".terms-card",
        { opacity: 0, y: 40, scale: 0.97 },
        {
          opacity: 1,
          y: 0,
          scale: 1,
          duration: 0.8,
          ease: "power3.out",
          stagger: 0.1,
          scrollTrigger: {
            trigger: ".terms-card",
            start: "top 85%",
          },
        }
      );

      // HOVER PREMIUM
      gsap.utils.toArray<HTMLElement>(".terms-card").forEach((card) => {
        card.addEventListener("mouseenter", () => {
          gsap.to(card, {
            y: -6,
            scale: 1.01,
            duration: 0.3,
            ease: "power2.out",
          });
        });

        card.addEventListener("mouseleave", () => {
          gsap.to(card, {
            y: 0,
            scale: 1,
            duration: 0.3,
            ease: "power2.out",
          });
        });
      });
    }, containerRef);

    return () => ctx.revert();
  }, []);

  return (
    <div
      ref={containerRef}
      className="w-full min-h-screen bg-gradient-to-b from-white via-gray-50 to-white"
    >
      <div className="max-w-6xl mx-auto px-4 py-20 flex flex-col gap-16">
        {/* HERO */}
        <div ref={headerRef} className="text-center flex flex-col gap-6">
          <div className="mx-auto w-20 h-20 rounded-full bg-gradient-to-br from-[#6852d6] to-[#5841c5] flex items-center justify-center text-3xl shadow-lg">
            📋
          </div>
          <h1 className="text-2xl md:text-4xl font-bold text-gray-900">
            Conditions Générales
          </h1>
          <p className="text-gray-600 max-w-2xl mx-auto">
            Consultez nos conditions d'utilisation et politiques de protection
            des données.
          </p>
        </div>

        {/* CONTENT */}
        {sections.map((section, index) => (
          <section
            key={index}
            className="terms-section flex flex-col gap-8"
          >
            <h2 className="text-xl md:text-3xl font-bold text-gray-900">
              {section.title}
            </h2>

            {section.content && (
              <p className="text-gray-700 leading-relaxed">
                {section.content}
              </p>
            )}

            {section.items && (
              <ul className="bg-blue-50 p-6 rounded-xl flex flex-col gap-3">
                {section.items.map((item, i) => (
                  <li key={i} className="flex gap-2 text-gray-700">
                    <span className="text-[#6852d6]">→</span>
                    {item}
                  </li>
                ))}
              </ul>
            )}

            {section.subsections && (
              <div className="grid gap-6">
                {section.subsections.map((sub, i) => (
                  <div
                    key={i}
                    className="terms-card bg-white border rounded-2xl p-6 shadow-sm"
                  >
                    <h3 className="font-bold text-lg md:text-xl mb-3">
                      {sub.subtitle}
                    </h3>

                    {sub.content && (
                      <p className="text-gray-700">
                        {sub.content}
                      </p>
                    )}

                    {sub.suspiciousItems && (
                      <ul className="mt-4 text-red-700 flex flex-col gap-2">
                        {sub.suspiciousItems.map((item, j) => (
                          <li key={j} className="flex gap-2">
                            <span>✗</span>
                            {item}
                          </li>
                        ))}
                      </ul>
                    )}
                  </div>
                ))}
              </div>
            )}
          </section>
        ))}
      </div>
    </div>
  );
};
