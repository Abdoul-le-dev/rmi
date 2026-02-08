import { useEffect, useRef } from "react";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

export const PrivacyPolicyPage = (): JSX.Element => {
  //
  const containerRef = useRef<HTMLDivElement>(null);

  const sections = [
    {
      title: "Politique de Confidentialité",
      icon: "🔐",
      subsections: [
        {
          subtitle: "1. Collecte des informations personnelles",
          content:
            "Nous collectons des informations personnelles lorsque vous créez un compte, achetez des cours, vous abonnez à des services, participez à des classes en direct, réservez des séances de coaching, ou interagissez avec notre support client. Les informations collectées peuvent inclure votre nom, adresse, adresse e-mail, numéro de téléphone, et informations de paiement.",
        },
        {
          subtitle: "2. Utilisation des informations personnelles",
          content:
            "Les informations personnelles que nous collectons sont utilisées pour :",
          items: [
            "Fournir, gérer et améliorer nos services.",
            "Traiter vos commandes et transactions.",
            "Communiquer avec vous, notamment pour le support client.",
            "Personnaliser votre expérience sur notre plateforme.",
            "Respecter nos obligations légales.",
          ],
        },
        {
          subtitle: "3. Partage des informations personnelles",
          content:
            "Nous ne partageons vos informations personnelles avec des tiers que dans les cas suivants :",
          items: [
            "Avec votre consentement explicite.",
            "Pour des raisons légales ou réglementaires.",
            "Avec nos prestataires de services sous obligation de confidentialité.",
          ],
        },
        {
          subtitle: "4. Sécurité des informations personnelles",
          content:
            "Nous mettons en œuvre des mesures de sécurité rigoureuses pour protéger vos informations personnelles contre l'accès non autorisé, la divulgation, la modification ou la destruction. Cependant, aucune méthode n'est infaillible.",
        },
        {
          subtitle: "5. Droits des utilisateurs",
          content:
            "Conformément à la législation béninoise, vous disposez d’un droit d’accès, de rectification, de suppression et de portabilité de vos données. Contact : support@rmiclass.net",
        },
      ],
    },
    {
      title: "Politique de Cookies",
      icon: "🍪",
      subsections: [
        {
          subtitle: "1. Qu'est-ce qu'un cookie ?",
          content:
            "Un cookie est un fichier texte stocké sur votre appareil permettant de mémoriser vos préférences.",
        },
        {
          subtitle: "2. Types de cookies utilisés",
          items: [
            "Cookies strictement nécessaires",
            "Cookies de performance",
            "Cookies de fonctionnalité",
            "Cookies publicitaires",
          ],
        },
        {
          subtitle: "3. Gestion des cookies",
          content:
            "Vous pouvez accepter ou refuser les cookies non essentiels via la bannière prévue à cet effet.",
        },
        {
          subtitle: "4. Durée de conservation",
          content:
            "Les cookies peuvent être temporaires (session) ou persistants selon leur finalité.",
        },
        {
          subtitle: "5. Contact",
          content:
            "Pour toute question relative aux cookies : support@rmiclass.net",
        },
      ],
    },
  ];

  useEffect(() => {
    if (!containerRef.current) return;

    const ctx = gsap.context(() => {
      gsap.fromTo(
        ".policy-hero",
        { opacity: 0, y: 30 },
        { opacity: 1, y: 0, duration: 0.8, ease: "power3.out" }
      );

      gsap.fromTo(
        ".policy-section",
        { opacity: 0, y: 50 },
        {
          opacity: 1,
          y: 0,
          duration: 0.9,
          stagger: 0.25,
          ease: "power3.out",
          scrollTrigger: {
            trigger: ".policy-content",
            start: "top 80%",
          },
        }
      );

      gsap.fromTo(
        ".policy-card",
        { opacity: 0, y: 40 },
        {
          opacity: 1,
          y: 0,
          duration: 0.7,
          stagger: 0.12,
          ease: "power3.out",
          scrollTrigger: {
            trigger: ".policy-content",
            start: "top 70%",
          },
        }
      );
    }, containerRef);

    return () => ctx.revert();
  }, []);

  return (
    <div
      ref={containerRef}
      className="w-full min-h-screen bg-gradient-to-b from-white via-gray-50 to-white"
    >
      <div className="flex flex-col items-center gap-16 px-4 md:px-6 lg:px-8 py-20 max-w-6xl mx-auto">
        {/* HERO */}
        <div className="policy-hero flex flex-col items-center gap-6 text-center">
          <div className="w-20 h-20 rounded-full bg-gradient-to-br from-[#6852d6] to-[#5841c5] flex items-center justify-center shadow-lg">
            <span className="text-3xl">🔐</span>
          </div>

          <h1 className="text-2xl md:text-4xl lg:text-6xl font-bold">
            Politique de Confidentialité & Cookies
          </h1>

          <p className="max-w-2xl text-gray-600 text-base md:text-lg [font-family:'Sora',Helvetica]">
            Transparence, sécurité et respect de vos données personnelles
          </p>
        </div>

        <div className="policy-content flex flex-col gap-20 w-full">
          {sections.map((section, sectionIndex) => (
            <section key={sectionIndex} className="policy-section flex flex-col gap-10">
              <div className="flex items-center gap-4 w-full  md:w-auto">
                <div className="md:w-14 md:h-14 w-10 h-10 shrink-0 rounded-full bg-gradient-to-br from-[#6852d6] to-[#a67fe0] flex items-center justify-center shadow-md">
                  <span className="text-xl md:text-2xl">{section.icon}</span>
                </div>
                <h2 className="text-xl md:text-3xl font-bold">
                  {section.title}
                </h2>
              </div>

              <div className="flex flex-col gap-8">
                {section.subsections.map((sub, index) => (
                  <div
                    key={index}
                    className="policy-card bg-white border border-gray-200 rounded-2xl p-6 md:p-8 transition-all hover:border-[#6852d6] hover:shadow-lg"
                  >
                    <h3 className="text-lg md:text-xl font-bold mb-4 [font-family:'Archivo',Helvetica]">
                      {sub.subtitle}
                    </h3>

                    {sub.content && (
                      <p className="text-sm md:text-base text-gray-600 leading-relaxed [font-family:'Sora',Helvetica] mb-4">
                        {sub.content}
                      </p>
                    )}

                    {sub.items && (
                      <ul className="flex flex-col gap-3 bg-indigo-50 border border-indigo-200 rounded-xl p-5">
                        {sub.items.map((item, i) => (
                          <li
                            key={i}
                            className="text-sm md:text-base flex gap-3 [font-family:'Sora',Helvetica]"
                          >
                            <span className="text-[#6852d6] font-bold">•</span>
                            <span>{item}</span>
                          </li>
                        ))}
                      </ul>
                    )}
                  </div>
                ))}
              </div>
            </section>
          ))}
        </div>

        <div className="w-full bg-indigo-50 border border-indigo-200 rounded-2xl p-6 md:p-8 text-center">
          <p className="text-sm md:text-base [font-family:'Sora',Helvetica]">
            <strong className="text-[#6852d6]">Besoin d’aide ?</strong>  
            Contactez-nous à <span className="font-semibold text-[#6852d6]">support@rmiclass.net</span>
          </p>
        </div>
      </div>
    </div>
  );
};
