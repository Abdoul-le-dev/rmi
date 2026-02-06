"use client";

import { useState, useRef } from "react";
import { ChevronDown } from "lucide-react";
import gsap from "gsap";
import { useGSAP } from "@gsap/react";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(useGSAP, ScrollTrigger);

interface FAQItem {
  question: string;
  answer: string;
  category?: string;
}

interface FAQCategory {
  title: string;
  items: FAQItem[];
}

const faqCategories: FAQCategory[] = [
  {
    title: "Inscription et Accès à la Plateforme",
    items: [
      {
        question: "Comment puis-je m'inscrire sur la plateforme RMI Class ?",
        answer:
          "Pour vous inscrire, rendez-vous sur notre site et cliquez sur « S'inscrire ». Remplissez le formulaire avec vos informations personnelles, choisissez un mot de passe sécurisé et suivez les instructions. Vous recevrez un e-mail de confirmation avec un code de validation. Renseignez le code pour finaliser votre inscription.",
      },
      {
        question: "Que faire si je rencontre des difficultés lors de l'inscription ?",
        answer:
          "Vérifiez que toutes les informations saisies sont correctes. Si le problème persiste, contactez notre support client via support@rmiclass.net ou le chat en ligne.",
      },
      {
        question: "Est-ce que mon accès à la plateforme est permanent après avoir payé les frais ?",
        answer:
          "Oui, après paiement, vous bénéficiez d'un accès permanent à la plateforme et à tous les contenus du programme. Vous recevrez également toutes les mises à jour sans frais supplémentaires.",
      },
      {
        question: "Comment accéder aux cours après l'inscription ?",
        answer:
          "Une fois inscrit et connecté, accédez à vos cours en cliquant sur « FORMATIONS » dans votre tableau de bord. Sélectionnez le cours souhaité pour commencer votre apprentissage.",
      },
      {
        question: "Quels sont les navigateurs recommandés ?",
        answer:
          "Pour une expérience optimale, utilisez Google Chrome, Mozilla Firefox ou Safari. Assurez-vous que votre navigateur est à jour et que votre compte est bien connecté.",
      },
      {
        question: "Comment récupérer mon mot de passe si je l'ai oublié ?",
        answer:
          "Cliquez sur « Mot de passe oublié » sur la page de connexion. Entrez l'adresse e-mail associée à votre compte et suivez les instructions du lien de réinitialisation reçu.",
      },
    ],
  },
  {
    title: "Formation et Contenus",
    items: [
      {
        question: "Quels sont les contenus inclus dans la formation complète ?",
        answer:
          "La formation complète comprend plus de 70 modules couvrant les bases du trading, l'analyse technique et fondamentale, des stratégies avancées et des exercices pratiques. Vous aurez accès à des indicateurs techniques exclusifs et des mises à jour régulières.",
      },
      {
        question: "Que faire si une situation d'apprentissage n'est pas accessible ?",
        answer:
          "Assurez-vous de suivre intégralement toutes les vidéos de la situation d'apprentissage précédente et de valider le quiz associé. L'algorithme empêche les sauts de modules.",
      },
      {
        question: "Quelles sont les prochaines étapes après avoir terminé la formation ?",
        answer:
          "Après avoir terminé la formation et reçu votre certificat, rejoignez la communauté RMI Class via un abonnement (mensuel, trimestriel ou annuel). Bénéficiez des live classes quotidiennes et des setups d'opportunités VIP.",
      },
      {
        question: "Comment accéder aux mises à jour des cours ?",
        answer:
          "Les mises à jour sont automatiquement ajoutées à votre compte. Vous serez informé par e-mail ou via votre tableau de bord. Aucun paiement supplémentaire n'est nécessaire si vous avez déjà acheté la formation complète.",
      },
      {
        question: "Quelle est la différence entre les modules standards et les contenus VIP ?",
        answer:
          "Les modules standards couvrent les bases et stratégies avancées. Les contenus VIP offrent accès à des setups de trading, analyses en temps réel et interaction exclusive avec les instructeurs. L'accès VIP est réservé aux membres de la formation complète.",
      },
    ],
  },
  {
    title: "Utilisation des Outils Techniques",
    items: [
      {
        question: "Quels sont les outils d'analyse fournis ?",
        answer:
          "La formation inclut des indicateurs techniques exclusifs, des modèles de graphique et des documents d'analyse technique conçus pour améliorer vos performances en trading.",
      },
      {
        question: "Où puis-je trouver les indicateurs RMI après les avoir téléchargés ?",
        answer:
          "Les indicateurs se trouvent par défaut dans le dossier Téléchargements (Download) de votre ordinateur. Déplacez-les ensuite dans le dossier « Indicators » de votre plateforme MetaTrader.",
      },
      {
        question: "Comment installer les indicateurs RMI sur MetaTrader ?",
        answer:
          "Téléchargez les fichiers depuis votre espace membre, puis copiez-les dans la section « Indicators » de MT4. Accédez aux indicateurs depuis la liste pour les ajouter à vos graphiques. Nos indicateurs sont compatibles avec MetaTrader 4.",
      },
      {
        question: "Comment utiliser efficacement les indicateurs ?",
        answer:
          "Suivez les instructions fournies dans les modules de formation. Combinez les indicateurs avec d'autres techniques d'analyse pour maximiser leur utilité. Pratiquez régulièrement et ajustez les paramètres selon votre stratégie.",
      },
      {
        question: "Que faire si un indicateur ne fonctionne pas correctement ?",
        answer:
          "Vérifiez d'abord que vous avez suivi toutes les étapes d'installation correctement. Si le problème persiste, contactez notre support client pour obtenir de l'aide.",
      },
    ],
  },
  {
    title: "Communauté VIP et Interaction",
    items: [
      {
        question: "Quelles sont les activités disponibles pour les membres de la communauté VIP ?",
        answer:
          "Les membres ont accès à des live classes quotidiennes, des setups de trading VIP, des forums de discussion et des événements exclusifs réservés aux membres.",
      },
      {
        question: "Comment puis-je participer aux live classes quotidiennes ?",
        answer:
          "Connectez-vous à votre compte RMI Class et cliquez sur « Live Classes » dans votre tableau de bord. Suivez les instructions pour rejoindre la session. Le calendrier est mis à jour chaque semaine.",
      },
      {
        question: "Qu'est-ce que le programme hebdomadaire ?",
        answer:
          "Le programme hebdomadaire détaille les activités et sessions prévues pour la semaine. Il est affiché dans votre backoffice et envoyé par e-mail pour vous aider à planifier votre apprentissage.",
      },
      {
        question: "Comment interagir avec d'autres membres ?",
        answer:
          "Utilisez le forum de discussion intégré à la plateforme pour partager vos expériences, poser des questions et échanger des idées. Participez également aux groupes de discussion pendant les live classes.",
      },
      {
        question: "Est-ce que les live classes sont incluses dans la formation complète ?",
        answer:
          "Non, les live classes quotidiennes nécessitent un abonnement (mensuel, trimestriel ou annuel) réservé exclusivement aux membres de la formation complète.",
      },
    ],
  },
  {
    title: "Tarification et Abonnements",
    items: [
      {
        question: "Quels sont les frais de formation ?",
        answer:
          "Les frais varient selon le cours choisi. Consultez la section « FORMATION » sur notre site web pour connaître les tarifs actuels. Des offres promotionnelles peuvent être disponibles à certaines périodes.",
      },
      {
        question: "Les frais sont-ils payables en une seule fois ou en plusieurs versements ?",
        answer: "Les frais de formation sont uniquement payables en une seule fois.",
      },
      {
        question: "Dois-je payer un supplément pour les séances de coaching privées ?",
        answer:
          "Les live classes, setups et interactions VIP sont inclus dans votre plan d'abonnement. Les séances de coaching privées nécessitent une tarification supplémentaire ponctuelle selon le coach et la durée.",
      },
      {
        question: "Quel est le coût des abonnements VIP ?",
        answer:
          "Les coûts varient selon la durée (mensuel, trimestriel ou annuel) et peuvent évoluer. Consultez la section « Communauté VIP » sur notre site pour les tarifs actuels.",
      },
      {
        question: "Est-ce que les prix des abonnements vont augmenter ?",
        answer:
          "Les prix peuvent être révisés en fonction de l'évolution des services. Si une augmentation est prévue, vous serez informé à l'avance et les tarifs actuels resteront valables jusqu'à la fin de votre période en cours.",
      },
      {
        question: "Quelles sont les options de paiement disponibles ?",
        answer:
          "Nous acceptons les paiements électroniques et virements bancaires. Vous pouvez choisir votre méthode préférée lors de l'inscription. Pour un pays sans option disponible, contactez-nous au +229 99 00 91 93.",
      },
      {
        question: "Puis-je accéder à la formation si je n'ai pas encore payé ?",
        answer:
          "L'accès aux cours et contenus est activé uniquement après le règlement des frais ou confirmation du plan de paiement.",
      },
    ],
  },
  {
    title: "Support et Assistance",
    items: [
      {
        question: "Comment contacter le support client ?",
        answer:
          "Contactez-nous via le chat en ligne sur notre site, par e-mail à support@rmiclass.net ou par téléphone au +229 99 00 91 93. Notre équipe est disponible pour vous aider.",
      },
      {
        question: "Que faire si je rencontre un problème technique ?",
        answer:
          "Essayez d'abord de rafraîchir la page ou de vous déconnecter/reconnecter. Si le problème persiste, contactez le support en décrivant le problème avec détails (captures, vidéos, etc.).",
      },
      {
        question: "Comment signaler une erreur dans mes certificats ou notes ?",
        answer:
          "Contactez notre support client en fournissant les détails nécessaires. Nous examinerons votre demande et apporterons les corrections dans les plus brefs délais.",
      },
      {
        question: "Comment obtenir le guide de performance du trader ?",
        answer:
          "Procédez directement à l'achat depuis votre espace membre. Pour plus d'assistance, contactez-nous au +229 99 00 91 93.",
      },
      {
        question: "Comment signaler une insatisfaction ?",
        answer:
          "Contactez notre support à support@rmiclass.net. Nous prenons vos retours très au sérieux. Si vous n'êtes toujours pas satisfait, adressez-vous à contact@royalmarketsinv.com.",
      },
      {
        question: "Quelles sont les heures de disponibilité du support ?",
        answer:
          "Notre support est disponible du lundi au samedi, de 9h00 à 18h00 GMT+1. En dehors de ces horaires, vous pouvez laisser un message et nous vous répondrons dès que possible.",
      },
    ],
  },
  {
    title: "Divers",
    items: [
      {
        question: "Qu'est-ce que la RMI Class et quels sont ses objectifs ?",
        answer:
          "RMI Class est une plateforme de formation en ligne dédiée au trading, visant à fournir des connaissances complètes et des outils pratiques pour aider les traders de tous niveaux à améliorer leurs compétences et réussir sur les marchés financiers.",
      },
      {
        question: "Est-ce que RMI Class offre des services d'investissement ?",
        answer:
          "Non, nous n'offrons pas de services d'investissement. Nous fournissons uniquement des formations, des conseils pédagogiques et des outils techniques pour prendre des décisions averties.",
      },
      {
        question: "Que faire si je veux quitter la communauté RMI Class ?",
        answer:
          "Vous pouvez désactiver votre compte en contactant le support client. Cette action est définitive et vous perdrez l'accès à tous les contenus et services.",
      },
      {
        question: "Comment suivre les évolutions de la plateforme ?",
        answer:
          "Abonnez-vous à notre newsletter et suivez-nous sur les réseaux sociaux, notamment notre canal Telegram https://t.me/rmiclass. Nous publions régulièrement du contenu sur nos nouvelles fonctionnalités.",
      },
      {
        question: "Comment contacter un responsable pour des partenariats ?",
        answer:
          "Pour propositions de partenariat ou offres spéciales, contactez-nous à contact@royalmarketsinv.com ou appelez le +229 99 00 91 93 pour réserver un rendez-vous.",
      },
    ],
  },
];

export const FAQAccordionSection = (): JSX.Element => {
  const [openIndex, setOpenIndex] = useState<string | null>(null);
  const containerRef = useRef<HTMLDivElement>(null);

  // Animation d'entrée des sections au scroll
  useGSAP(() => {
    const categories = gsap.utils.toArray(".faq-category-block");
    
    categories.forEach((category: any) => {
      gsap.from(category, {
        opacity: 0,
        y: 30,
        duration: 0.8,
        scrollTrigger: {
          trigger: category,
          start: "top 85%",
          toggleActions: "play none none none",
        },
      });
    });
  }, { scope: containerRef });

  const toggleItem = (id: string) => {
    const content = document.getElementById(`content-${id}`);
    const isOpening = openIndex !== id;

    // Animation de fermeture de l'ancien et ouverture du nouveau
    if (openIndex && openIndex !== id) {
      const oldContent = document.getElementById(`content-${openIndex}`);
      if (oldContent) gsap.to(oldContent, { height: 0, opacity: 0, duration: 0.3, ease: "power2.inOut" });
    }

    if (content) {
      gsap.to(content, {
        height: isOpening ? "auto" : 0,
        opacity: isOpening ? 1 : 0,
        duration: 0.4,
        ease: "power2.out",
      });
    }

    setOpenIndex(isOpening ? id : null);
  };

  return (
    <section ref={containerRef} className="flex flex-col items-center px-4 md:px-6 lg:px-8 pb-16 md:pb-24 w-full bg-gray-50/50">
      <div className="max-w-4xl w-full">
        <div className="space-y-12 md:space-y-16">
          {faqCategories.map((category, catIndex) => (
            <div key={catIndex} className="faq-category-block flex flex-col gap-6">
              <h2 className="text-xl md:text-2xl font-bold text-[#6852d6] flex items-center gap-3">
                <span className="w-8 h-1 bg-[#6852d6] rounded-full hidden md:block" />
                {category.title}
              </h2>

              <div className="space-y-4">
                {category.items.map((item, itemIndex) => {
                  const itemId = `${catIndex}-${itemIndex}`;
                  const isOpen = openIndex === itemId;

                  return (
                    <div
                      key={itemId}
                      className={`group border rounded-2xl overflow-hidden transition-all duration-300 bg-white ${
                        isOpen ? "border-[#6852d6] shadow-md" : "border-gray-200 hover:border-[#6852d6]/50"
                      }`}
                    >
                      <button
                        onClick={() => toggleItem(itemId)}
                        className="w-full px-6 py-5 flex items-center justify-between transition-colors gap-4 text-left"
                      >
                        <h3 className={`text-sm md:text-base font-bold transition-colors ${
                          isOpen ? "text-[#6852d6]" : "text-gray-900"
                        }`}>
                          {item.question}
                        </h3>
                        <div className={`p-1 rounded-full transition-all duration-300 ${
                          isOpen ? "bg-[#6852d6] text-white rotate-180" : "bg-gray-100 text-[#6852d6]"
                        }`}>
                          <ChevronDown className="w-4 h-4" />
                        </div>
                      </button>

                      <div
                        id={`content-${itemId}`}
                        className="h-0 opacity-0 overflow-hidden"
                      >
                        <div className="px-6 pb-6 pt-0">
                          <div className="h-[1px] w-full bg-gray-100 mb-4" />
                          <p className="text-sm md:text-base font-sora text-gray-600 leading-relaxed italic border-l-2 border-[#6852d6]/20 pl-4">
                            {item.answer}
                          </p>
                        </div>
                      </div>
                    </div>
                  );
                })}
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
};