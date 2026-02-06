import React, { useRef } from "react";
import gsap from "gsap";
import { useGSAP } from "@gsap/react";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(useGSAP, ScrollTrigger);

export const LegalNoticePage = (): JSX.Element => {
  const container = useRef<HTMLDivElement>(null);

  const sections = [
    {
      title: "Éditeur du site",
      content:
        "RMI Class est une plateforme éducative spécialisée dans la formation en trading, développée et gérée par la société Royal Markets Investment (RMI). Cette entreprise, reconnue pour son expertise et son engagement dans le secteur boursier, a créé RMI Class pour offrir des ressources éducatives complètes, incluant des cours en ligne, des séances de coaching privé et des classes en direct, visant à former les utilisateurs aux stratégies et techniques de trading.",
      items: [
        { label: "Raison social ou Dénomination", value: "ROYAL MARKETS INV (RMI)" },
        { label: "Forme juridique", value: "Société à Responsabilité Limitée" },
        {
          label: "Siège social",
          value:
            "Bénin, Cotonou, Ilot: 557, Quartier Sèdjro St Michel, Parcelle: 00, Maison: RAFIATOU ALAO, Tél: +229 99009193",
        },
        { label: "RCCM", value: "N° RCCM RB/COT/23 B 36708" },
        { label: "Email", value: "contact@royalmarketsinv.com" },
        { label: "Téléphone", value: "+229 97 20 31 88" },
      ],
    },
    {
      title: "Directeur de la publication",
      content: "Fiacre D. KPANOU",
    },
    {
      title: "Hébergeur du site",
      items: [
        { label: "Nom de l'hébergeur", value: "Namecheap, Inc" },
        {
          label: "Adresse de l'hébergeur",
          value: "4600 East Washington Street, Suite 300, Phoenix, AZ 85034, USA",
        },
      ],
    },
    {
      title: "Propriété intellectuelle",
      content:
        "Tous les contenus présents sur le site RMI Class, y compris les textes, images, logos, graphismes, vidéos, logiciels et bases de données, sont protégés par les droits d'auteur et autres droits de propriété intellectuelle. Ces contenus sont la propriété exclusive de Royal Markets Investment (RMI) ou de ses partenaires. Toute reproduction, distribution, modification ou utilisation de ces contenus sans autorisation préalable est strictement interdite.",
    },
    {
      title: "Protection des données personnelles",
      content:
        "Conformément à la législation béninoise en matière de protection des données personnelles, RMI Class s'engage à protéger les informations personnelles de ses utilisateurs. Pour plus de détails, veuillez consulter notre section Données personnelles.",
    },
    {
      title: "Cookies",
      content:
        "Le site RMI Class utilise des cookies pour améliorer l'expérience utilisateur. Les cookies permettent notamment de suivre la navigation des utilisateurs et de proposer des contenus personnalisés. Pour plus d'informations sur l'utilisation des cookies, veuillez consulter notre section Politiques de Confidentialité et Cookies.",
    },
    {
      title: "Responsabilité",
      content:
        "RMI Class met tout en œuvre pour offrir des informations précises et à jour sur son site. Toutefois, RMI ne peut garantir l'exactitude, la complétude ou la mise à jour des informations diffusées sur le site. RMI décline toute responsabilité en cas d'erreurs ou d'omissions. L'utilisateur est seul responsable de l'utilisation des informations fournies sur le site.",
    },
    {
      title: "Liens hypertextes",
      content:
        "Le site RMI Class peut inclure des liens hypertextes redirigeant vers divers sites internet externes. Bien que nous sélectionnions ces liens avec soin, Royal Markets Investment (RMI) ne peut garantir ni la qualité, ni l'exactitude, ni la pertinence des informations présentées sur ces sites tiers. En conséquence, RMI décline toute responsabilité quant au contenu de ces sites externes, ainsi qu'à l'utilisation qui peut en être faite par les utilisateurs.",
    },
    {
      title: "Droit applicable et juridiction compétente",
      content:
        "Les présentes mentions légales sont régies et interprétées conformément à la législation en vigueur en République du Bénin. En cas de litige découlant de l'utilisation du site RMI Class ou lié à son contenu, les parties s'efforceront de trouver une solution amiable. À défaut de résolution amiable, le différend sera porté devant les juridictions compétentes du ressort de la Cour d'Appel de Cotonou, Bénin, qui auront la compétence exclusive pour le résoudre.",
    },
    {
      title: "Contact",
      content:
        "Pour toute question ou demande d'information concernant les mentions légales du site RMI Class, nous vous invitons à contacter notre équipe de support. Vous pouvez nous joindre par e-mail à l'adresse suivante : support@rmiclass.net",
    },
  ];

  useGSAP(() => {
    // Animation d'entrée du header (titre et icône)
    gsap.from(".animate-header", {
      y: 40,
      opacity: 0,
      duration: 1,
      stagger: 0.2,
      ease: "power4.out"
    });

    // Animation de chaque bloc de section au scroll
    const cards = gsap.utils.toArray<HTMLElement>(".legal-card");
    cards.forEach((card) => {
      gsap.from(card, {
        scrollTrigger: {
          trigger: card,
          start: "top 90%",
          toggleActions: "play none none none"
        },
        y: 60,
        opacity: 0,
        duration: 0.8,
        ease: "power2.out"
      });
    });
  }, { scope: container });

  return (
    <div ref={container} className="w-full min-h-screen bg-gradient-to-b from-white via-gray-50 to-white">
      <div className="flex flex-col items-center gap-10 md:gap-14 lg:gap-16 px-4 md:px-6 lg:px-8 py-16 md:py-20 lg:py-28 w-full max-w-6xl mx-auto">
        <div className="flex flex-col items-center gap-6 w-full text-center">
          <div className="animate-header flex items-center justify-center w-16 h-16 md:w-20 md:h-20 rounded-full bg-gradient-to-br from-[#6852d6] to-[#5841c5] shadow-lg">
            <span className="text-2xl md:text-3xl">⚖️</span>
          </div>
          <h1 className="animate-header text-2xl md:text-5xl lg:text-6xl font-bold text-center [font-family:'Archivo',Helvetica] text-rmi-colors-stylesshark">
            Mentions légales
          </h1>
          <p className="animate-header text-center text-gray-600 [font-family:'Sora',Helvetica] text-base md:text-lg max-w-2xl leading-relaxed">
            Informations légales et administratives concernant le site RMI Class
          </p>
        </div>

        <div className="w-full h-1 bg-gradient-to-r from-transparent via-[#6852d6] to-transparent rounded-full"></div>

        <div className="flex flex-col gap-8 md:gap-10 w-full">
          {sections.map((section, index) => (
            <div
              key={index}
              className="legal-card group bg-white rounded-2xl border border-gray-200 p-6 md:p-8 lg:p-10 shadow-sm hover:shadow-lg hover:border-[#6852d6] transition-all duration-300"
            >
              <div className="flex flex-col md:flex-row items-start gap-4 md:gap-6">
                <div className="flex-shrink-0 flex items-center justify-center w-10 h-10 md:w-12 md:h-12 rounded-full bg-gradient-to-br from-[#6852d6] from-30% to-[#a67fe0] shadow-md group-hover:shadow-lg transition-shadow">
                  <span className="text-lg md:text-xl">
                    {index === 0 && '🏢'}
                    {index === 1 && '👤'}
                    {index === 2 && '🌐'}
                    {index === 3 && '©️'}
                    {index === 4 && '🔒'}
                    {index === 5 && '🍪'}
                    {index === 6 && '⚠️'}
                    {index === 7 && '🔗'}
                    {index === 8 && '⚖️'}
                    {index === 9 && '✉️'}
                  </span>
                </div>
                <div className="flex-1 min-w-0">
                  <h2 className="text-xl md:text-2xl lg:text-3xl font-bold [font-family:'Archivo',Helvetica] text-rmi-colors-stylesshark mb-4 group-hover:text-[#6852d6] transition-colors">
                    {section.title}
                  </h2>

                  {section.content && (
                    <p className="[font-family:'Sora',Helvetica] text-sm md:text-base text-app-shark leading-relaxed md:leading-8 mb-6">
                      {section.content}
                    </p>
                  )}

                  {section.items && (
                    <div className="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-5 md:p-6 border border-gray-200">
                      <ul className="flex flex-col gap-4">
                        {section.items.map((item, itemIndex) => (
                          <li
                            key={itemIndex}
                            className="flex flex-col md:flex-row md:items-start gap-2 md:gap-4"
                          >
                            <span className="font-bold text-[#6852d6] min-w-fit [font-family:'Archivo',Helvetica] text-sm md:text-base">
                              {item.label}:
                            </span>
                            <span className="[font-family:'Sora',Helvetica] text-sm md:text-base text-app-shark leading-relaxed flex-1">
                              {item.value}
                            </span>
                          </li>
                        ))}
                      </ul>
                    </div>
                  )}
                </div>
              </div>
            </div>
          ))}
        </div>

        <div className="w-full h-1 bg-gradient-to-r from-transparent via-[#6852d6] to-transparent rounded-full mt-8"></div>

        <div className="text-center py-6">
          <p className="[font-family:'Sora',Helvetica] text-sm md:text-base text-gray-600">
            © 2026 RMI Class. Tous droits réservés.
          </p>
        </div>
      </div>
    </div>
  );
};