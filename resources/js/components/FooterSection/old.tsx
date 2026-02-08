"use client";

import { useRef } from "react";
import { Link } from "react-router-dom";
import { Facebook, Twitter, Instagram, Youtube, Mail, Phone } from "lucide-react";
import { Separator } from "../ui/separator";
import gsap from "gsap";
import { useGSAP } from "@gsap/react";
import { ScrollTrigger } from "gsap/ScrollTrigger";

if (typeof window !== "undefined") {
    gsap.registerPlugin(ScrollTrigger);
}

const socialLinks = [
    { icon: Twitter, href: "#", label: "Twitter" },
    { icon: Instagram, href: "#", label: "Instagram" },
    { icon: Facebook, href: "#", label: "Facebook" },
    { icon: Youtube, href: "#", label: "Youtube" },
];

const aboutLinks = [
    { label: "Notre Histoire", href: "/about" },
    { label: "L'Équipe & Instructeurs", href: "/instructeurs" },
    { label: "Témoignages", href: "/communaute" }, // Redirige vers la section preuve sociale
    { label: "Nos Produits", href: "/produits" },
];

const formationLinks = [
    { label: "Initiation au Trading", href: "/initiation-au-trading" },
    { label: "Devenir Trader Pro", href: "/devenir-trader-pro" },
    { label: "Live Class", href: "/communaute" },
    { label: "Communauté VIP", href: "/communaute" },
];

const domainsLinks = [
    { label: "Marché du Forex", href: "#" },
    { label: "Cryptomonnaies", href: "#" },
    { label: "Développement Personnel", href: "#" },
    { label: "Management des émotions", href: "#" },
];

const legalLinks = [
    { label: "Mentions légales", href: "/mentions-legales" },
    { label: "Politique de confidentialité", href: "/politique-confidentialite" },
    { label: "Conditions générales", href: "/conditions-generales" },
];

export const FooterSection = (): JSX.Element => {
    const footerRef = useRef<HTMLDivElement>(null);

    useGSAP(() => {
        if (!footerRef.current) return;

        gsap.from(".footer-col", {
            scrollTrigger: {
                trigger: footerRef.current,
                start: "top 90%",
            },
            y: 20,
            opacity: 0,
            duration: 0.6,
            stagger: 0.1,
            ease: "power2.out",
        });
    }, { scope: footerRef });

    return (
        <footer ref={footerRef} className="border-t border-gray-100 bg-white w-full">
            <div className="container mx-auto px-4 md:px-6 lg:px-8 py-16 md:py-20">

                {/* Grille Principale */}
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-8 mb-16">

                    {/* Bloc Marque (4 colonnes) */}
                    <div className="footer-col sm:col-span-2 lg:col-span-4 flex flex-col gap-6">
                        <Link to="/">
                            <img
                                className="w-[150px] md:w-[180px] h-auto object-contain"
                                alt="RMI Class Logo"
                                src="/5-17-1.png"
                            />
                        </Link>
                        <p className="font-sora text-md text-gray-500 leading-relaxed max-w-xs">
                            La RMI Class est une plateforme d'élite dédiée à l'éducation financière. Nous formons la prochaine génération de traders rentables.
                        </p>

                        <div className="flex flex-col gap-3">
                            <a href="tel:+22999009193" className="flex items-center gap-3 text-md font-sora text-gray-600 hover:text-[#6852d6] transition-colors w-fit">
                                <Phone className="w-4 h-4 text-[#6852d6]" /> +229 99009193
                            </a>
                            <a href="mailto:support@rmiclass.net" className="flex items-center gap-3 text-md font-sora text-gray-600 hover:text-[#6852d6] transition-colors w-fit">
                                <Mail className="w-4 h-4 text-[#6852d6]" /> support@rmiclass.net
                            </a>
                        </div>

                        <div className="flex gap-3">
                            {socialLinks.map((social, i) => {
                                const Icon = social.icon;
                                return (
                                    <a key={i} href={social.href} className="w-9 h-9 rounded-lg bg-gray-50 text-gray-400 flex items-center justify-center hover:bg-[#6852d6] hover:text-white transition-all duration-300">
                                        <Icon className="w-4 h-4" />
                                    </a>
                                );
                            })}
                        </div>
                    </div>

                    {/* Navigation (2 colonnes) */}
                    <div className="footer-col lg:col-span-2 flex flex-col gap-5">
                        <h3 className="font-Archivo font-bold text-gray-900 text-md uppercase tracking-wider">À Propos</h3>
                        <nav className="flex flex-col gap-3">
                            {aboutLinks.map((link, i) => (
                                <Link key={i} to={link.href} className="text-md font-sora text-gray-500 hover:text-[#6852d6] transition-colors">{link.label}</Link>
                            ))}
                        </nav>
                    </div>

                    {/* Formations (3 colonnes) */}
                    <div className="footer-col lg:col-span-3 flex flex-col gap-5">
                        <h3 className="font-Archivo font-bold text-gray-900 text-md uppercase tracking-wider">Formations</h3>
                        <nav className="flex flex-col gap-3">
                            {formationLinks.map((link, i) => (
                                <Link key={i} to={link.href} className="text-md font-sora text-gray-500 hover:text-[#6852d6] transition-colors">{link.label}</Link>
                            ))}
                        </nav>
                    </div>

                    {/* Domaines (3 colonnes) */}
                    <div className="footer-col lg:col-span-3 flex flex-col gap-5">
                        <h3 className="font-Archivo font-bold text-gray-900 text-md uppercase tracking-wider">Expertises</h3>
                        <nav className="flex flex-col gap-3">
                            {domainsLinks.map((link, i) => (
                                <span key={i} className="text-md font-sora text-gray-500 flex items-center gap-2 cursor-default group hover:text-[#6852d6] transition-colors">
                                    <span className="w-1 h-1 rounded-full bg-gray-300 group-hover:bg-[#6852d6]" />
                                    {link.label}
                                </span>
                            ))}
                        </nav>
                        <Link to="/register" className="mt-4 px-5 py-3 bg-[#6852d6] text-white text-base font-bold font-sora rounded-xl text-center hover:shadow-lg hover:shadow-[#6852d6]/30 transition-all">
                            Démarrer mon cursus
                        </Link>
                    </div>

                </div>

                <Separator className="bg-gray-100 mb-8" />

                {/* Bottom Bar */}
                <div className="flex flex-col md:flex-row items-center justify-between gap-6">
                    <p className="font-sora text-[12px] text-gray-400">
                        © 2026 RMI Class. Tous droits réservés.
                    </p>

                    <nav className="flex flex-wrap items-center justify-center gap-6">
                        {legalLinks.map((link, i) => (
                            <Link key={i} to={link.href} className="text-[11px] font-sora font-medium uppercase tracking-tighter text-gray-400 hover:text-[#6852d6] transition-colors">
                                {link.label}
                            </Link>
                        ))}
                    </nav>
                </div>
            </div>
        </footer>
    );
};