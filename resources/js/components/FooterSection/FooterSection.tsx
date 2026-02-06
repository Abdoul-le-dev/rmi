"use client";

import { useRef, useState } from "react";
import { Link } from "react-router-dom";
import {
  Facebook,
  Twitter,
  Instagram,
  Youtube,
  Mail,
  Phone,
  ArrowRight,
  CheckCircle2
} from "lucide-react";
import { Separator } from "../ui/separator";
import gsap from "gsap";
import { useGSAP } from "@gsap/react";
import { ScrollTrigger } from "gsap/ScrollTrigger";

if (typeof window !== "undefined") {
  gsap.registerPlugin(ScrollTrigger);
}

// --- Données ---
const socialLinks = [
  { icon: Twitter, href: "#", label: "Twitter" },
  { icon: Instagram, href: "#", label: "Instagram" },
  { icon: Facebook, href: "#", label: "Facebook" },
  { icon: Youtube, href: "#", label: "Youtube" },
];

const aboutLinks = [
  { label: "Notre Histoire", href: "/about" },
  { label: "L'Équipe & Instructeurs", href: "/instructeurs" },
  { label: "Témoignages & Avis", href: "/communaute" },
  { label: "Nos Produits", href: "/produits" },
];

const formationLinks = [
  { label: "Initiation au Trading", href: "/initiation-au-trading" },
  { label: "Devenir Trader Pro", href: "/devenir-trader-pro" },
  { label: "Live Class", href: "/communaute" },
  { label: "Communauté VIP", href: "/communaute" },
];

const legalLinks = [
  { label: "Mentions légales", href: "/mentions-legales" },
  { label: "Confidentialité", href: "/politique-confidentialite" },
  { label: "Conditions générales", href: "/conditions-generales" },
];

export const FooterSection = (): JSX.Element => {
  const footerRef = useRef<HTMLDivElement>(null);
  const [email, setEmail] = useState("");

  useGSAP(() => {
    if (!footerRef.current) return;

    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: footerRef.current,
        start: "top 80%",
      }
    });

    // 1. Apparition douce du bloc Newsletter
    tl.from(".newsletter-box", {
      y: 30,
      opacity: 0,
      duration: 0.8,
      ease: "power3.out"
    });

    // 2. Apparition en cascade des colonnes
    tl.from(".footer-col", {
      y: 20,
      opacity: 0,
      duration: 0.6,
      stagger: 0.1,
      ease: "power2.out",
    }, "-=0.4");

  }, { scope: footerRef });

  // Animation Hover Lien (Décalage vers la droite + Couleur Violette)
  const handleLinkHover = (e: React.MouseEvent<HTMLAnchorElement>, isEnter: boolean) => {
    gsap.to(e.currentTarget, {
      x: isEnter ? 4 : 0,
      color: isEnter ? "#6852d6" : "#64748b", // slate-500 -> brand
      duration: 0.3,
      ease: "power2.out"
    });
  };

  return (
    <footer ref={footerRef} className="bg-slate-50 w-full border-t border-slate-200 relative overflow-hidden">

      <div className="absolute top-0 right-0 w-[600px] h-[600px] bg-[#6852d6] opacity-[0.03] blur-[120px] rounded-full pointer-events-none translate-x-1/2 -translate-y-1/2" />

      <div className="container mx-auto px-4 md:px-6 lg:px-8 py-16 md:py-20 relative z-10">

        {/* --- PARTIE 1 : NEWSLETTER PREMIUM --- 
            C'est ici qu'on capture l'attention. Un fond blanc sur le fond gris clair pour créer du relief.
        */}
        <div className="newsletter-box bg-white rounded-3xl p-8 md:p-10 shadow-xl shadow-slate-200/50 mb-20 border border-slate-100 flex flex-col lg:flex-row items-center justify-between gap-10">
          <div className="lg:w-1/2 space-y-4">
            <h3 className="font-Archivo font-bold text-2xl md:text-3xl text-slate-900">
              Restez informé des marchés
            </h3>
            <p className="font-sora text-slate-500 text-base leading-relaxed">
              Rejoignez <span className="text-[#6852d6] font-bold">2,500+ traders</span> qui reçoivent nos analyses et conseils exclusifs chaque semaine. Pas de spam, que de la valeur.
            </p>
            <div className="flex gap-4 pt-2">
              <div className="flex items-center gap-2 text-sm text-slate-600 font-sora">
                <CheckCircle2 className="w-4 h-4 text-[#6852d6]" /> Analyses Gratuites
              </div>
              <div className="flex items-center gap-2 text-sm text-slate-600 font-sora">
                <CheckCircle2 className="w-4 h-4 text-[#6852d6]" /> Webinaires VIP
              </div>
            </div>
          </div>

          <div className="w-full lg:w-5/12">
            <form className="flex flex-col sm:flex-row gap-3" onSubmit={(e) => e.preventDefault()}>
              <input
                type="email"
                placeholder="Votre adresse email pro"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                className="flex-1 bg-slate-50 border border-slate-200 text-slate-800 text-sm font-sora rounded-xl px-5 py-4 focus:outline-none focus:border-[#6852d6] focus:ring-2 focus:ring-[#6852d6]/20 transition-all placeholder:text-slate-400"
              />
              <button
                type="submit"
                className="px-6 py-4 bg-[#6852d6] hover:bg-[#5642b0] text-white font-bold font-sora rounded-xl transition-all shadow-lg shadow-[#6852d6]/20 hover:shadow-[#6852d6]/40 flex items-center justify-center gap-2 whitespace-nowrap"
              >
                S'inscrire
                <ArrowRight className="w-4 h-4" />
              </button>
            </form>
            <p className="text-md text-slate-400 mt-3 font-sora pl-1">
              En cliquant, vous acceptez notre politique de confidentialité.
            </p>
          </div>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-12 mb-16">

          <div className="footer-col md:col-span-2 lg:col-span-4 space-y-6">
            <Link to="/">
              <img
                className="w-[160px] h-auto object-contain"
                alt="RMI Class Logo"
                src="/5-17-1.png"
              />
            </Link>
            <p className="font-sora text-slate-500 leading-relaxed text-base pr-4">
              L'excellence pédagogique au service de votre liberté financière. RMI Class forme l'élite des traders de demain avec rigueur et passion.
            </p>

            <div className="flex gap-3 pt-2">
              {socialLinks.map((social, i) => {
                const Icon = social.icon;
                return (
                  <a key={i} href={social.href} className="group w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-400 flex items-center justify-center hover:bg-[#6852d6] hover:border-[#6852d6] hover:text-white transition-all duration-300 shadow-sm hover:shadow-lg hover:shadow-[#6852d6]/30">
                    <Icon className="w-4 h-4 group-hover:scale-110 transition-transform" />
                  </a>
                );
              })}
            </div>
          </div>

          <div className="footer-col lg:col-span-2 space-y-6">
            <h3 className="font-Archivo font-bold text-slate-900 text-sm uppercase tracking-wider">À Propos</h3>
            <nav className="flex flex-col gap-3">
              {aboutLinks.map((link, i) => (
                <Link
                  key={i}
                  to={link.href}
                  className="text-base font-sora text-slate-500"
                  onMouseEnter={(e) => handleLinkHover(e, true)}
                  onMouseLeave={(e) => handleLinkHover(e, false)}
                >
                  {link.label}
                </Link>
              ))}
            </nav>
          </div>

          <div className="footer-col lg:col-span-3 space-y-6">
            <h3 className="font-Archivo font-bold text-slate-900 text-base uppercase tracking-wider">Formations</h3>
            <nav className="flex flex-col gap-3">
              {formationLinks.map((link, i) => (
                <Link
                  key={i}
                  to={link.href}
                  className="text-base font-sora text-slate-500"
                  onMouseEnter={(e) => handleLinkHover(e, true)}
                  onMouseLeave={(e) => handleLinkHover(e, false)}
                >
                  {link.label}
                </Link>
              ))}
            </nav>
          </div>

          <div className="footer-col lg:col-span-3 space-y-6">
            <h3 className="font-Archivo font-bold text-slate-900 text-base uppercase tracking-wider">Nous Contacter</h3>
            <div className="space-y-4">
              <div className="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-start gap-4 hover:border-[#6852d6]/30 transition-colors group">
                <div className="p-2 bg-[#6852d6]/10 rounded-lg text-[#6852d6] group-hover:bg-[#6852d6] group-hover:text-white transition-colors">
                  <Phone className="w-5 h-5" />
                </div>
                <div>
                  <p className="text-base text-slate-400 font-sora uppercase mb-1">Téléphone</p>
                  <a href="tel:+22999009193" className="text-base font-bold text-slate-800 font-sora hover:text-[#6852d6] transition-colors block">
                    +229 99 00 91 93
                  </a>
                </div>
              </div>

              <div className="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-start gap-4 hover:border-[#6852d6]/30 transition-colors group">
                <div className="p-2 bg-[#6852d6]/10 rounded-lg text-[#6852d6] group-hover:bg-[#6852d6] group-hover:text-white transition-colors">
                  <Mail className="w-5 h-5" />
                </div>
                <div>
                  <p className="text-base text-slate-400 font-sora uppercase mb-1">Email</p>
                  <a href="mailto:support@rmiclass.net" className="text-base font-bold text-slate-800 font-sora hover:text-[#6852d6] transition-colors block">
                    support@rmiclass.net
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <Separator className="bg-slate-200 mb-8" />

        {/* --- PARTIE 3 : BOTTOM BAR --- */}
        <div className="flex flex-col md:flex-row items-center justify-between gap-6">
          <p className="text-md text-slate-400">
            © 2026 RMI Class. Tous droits réservés.
          </p>

          <nav className="flex flex-wrap items-center justify-center gap-8">
            {legalLinks.map((link, i) => (
              <Link key={i} to={link.href} className="text-md font-medium text-slate-400 hover:text-[#6852d6] transition-colors">
                {link.label}
              </Link>
            ))}
          </nav>
        </div>

      </div>
    </footer>
  );
};