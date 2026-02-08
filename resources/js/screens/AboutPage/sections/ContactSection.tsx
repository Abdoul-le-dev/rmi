"use client";

import { useRef } from "react";
import { Button } from "../../../components/ui/button";
import { Mail, Phone, MapPin, Facebook, Twitter, Instagram } from "lucide-react";
import gsap from "gsap";
import { useGSAP } from "@gsap/react";
import { ScrollTrigger } from "gsap/ScrollTrigger";

if (typeof window !== "undefined") {
  gsap.registerPlugin(ScrollTrigger);
}

export const ContactSection = (): JSX.Element => {
  const container = useRef<HTMLDivElement>(null);

  useGSAP(() => {
    if (!container.current) return;

    // Initialisation des états invisibles
    gsap.set(".contact-header, .contact-info, .contact-form", { 
      opacity: 0, 
      y: 30 
    });

    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: container.current,
        start: "top 80%",
        toggleActions: "play none none none",
      },
    });

    tl.to(".contact-header", {
      y: 0,
      opacity: 1,
      duration: 0.8,
      ease: "power3.out",
    })
    .to(".contact-info", {
      x: 0,
      y: 0,
      opacity: 1,
      duration: 0.8,
      stagger: 0.2,
      ease: "power2.out",
    }, "-=0.4")
    .to(".contact-form", {
      x: 0,
      y: 0,
      opacity: 1,
      duration: 1,
      ease: "power3.out",
    }, "-=0.8");

  }, { scope: container });

  return (
    <section ref={container} className="flex flex-col items-center px-4 md:px-6 lg:px-8 py-16 md:py-24 w-full bg-gray-50/50 overflow-hidden">
      <div className="max-w-6xl w-full">
        
        <div className="contact-header opacity-0 text-center mb-16 md:mb-20">
          <h2 className="text-2xl md:text-4xl font-bold mb-6 tracking-tight">
            Contactez <span className="text-[#6852d6]">Nous</span>
          </h2>
          <p className="max-w-2xl mx-auto text-base md:text-lg font-sora text-gray-600">
            Avez-vous une préoccupation ? Notre équipe d'experts est là pour vous guider vers la réussite.
          </p>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20">
          {/* Colonne Gauche : Infos */}
          <div className="flex flex-col gap-10">
            {[
              { icon: <Mail />, title: "Email", content: "contact@rmiclassdatin.com" },
              { icon: <Phone />, title: "Téléphone", content: "+229 97203188 / +229 09900993" },
              { icon: <MapPin />, title: "Localisation", content: "Bénin, Cotonou, Q Sodiva St Michel, CS27" },
            ].map((info, index) => (
              <div key={index} className="contact-info opacity-0 flex gap-6 group">
                <div className="flex-shrink-0 flex items-center justify-center md:h-14 md:w-14 w-10 h-10 rounded-md md:rounded-2xl bg-[#6852d6] text-white shadow-lg shadow-[#6852d6]/20 group-hover:scale-110 transition-transform duration-300">
                  {info.icon}
                </div>
                <div>
                  <h3 className="text-md md:text-xl font-bold font-Archivo text-gray-900">{info.title}</h3>
                  <p className="text-xs md:text-base font-sora text-gray-600 mt-1">{info.content}</p>
                </div>
              </div>
            ))}

            <div className="contact-info opacity-0 pt-6 border-t border-gray-200">
              <p className="text-sm font-bold font-sora text-gray-900 uppercase tracking-widest mb-6">
                Suivez l'actualité RMI :
              </p>
              <div className="flex gap-4">
                {[<Facebook />, <Twitter />, <Instagram />].map((social, i) => (
                  <a key={i} href="#" className="w-12 h-12 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-[#6852d6] hover:bg-[#6852d6] hover:text-white transition-all duration-300 shadow-sm">
                    {social}
                  </a>
                ))}
              </div>
            </div>
          </div>

          {/* Colonne Droite : Formulaire */}
          <div className="contact-form opacity-0 bg-white rounded-3xl p-8 md:p-12 shadow-2xl shadow-gray-200/50 border border-gray-100">
            <form className="flex flex-col gap-6">
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div className="space-y-2">
                  <label className="text-sm font-bold font-Archivo text-gray-700 ml-1">Nom</label>
                  <input type="text" placeholder="Votre nom" className="w-full px-5 py-4 rounded-xl border border-gray-200 focus:border-[#6852d6] focus:ring-2 focus:ring-[#6852d6]/20 outline-none transition-all font-sora" />
                </div>
                <div className="space-y-2">
                  <label className="text-sm font-bold font-Archivo text-gray-700 ml-1">Email</label>
                  <input type="email" placeholder="Votre email" className="w-full px-5 py-4 rounded-xl border border-gray-200 focus:border-[#6852d6] focus:ring-2 focus:ring-[#6852d6]/20 outline-none transition-all font-sora" />
                </div>
              </div>

              <div className="space-y-2">
                <label className="text-sm font-bold font-Archivo text-gray-700 ml-1">Objet</label>
                <input type="text" placeholder="Sujet de votre demande" className="w-full px-5 py-4 rounded-xl border border-gray-200 focus:border-[#6852d6] focus:ring-2 focus:ring-[#6852d6]/20 outline-none transition-all font-sora" />
              </div>

              <div className="space-y-2">
                <label className="text-sm font-bold font-Archivo text-gray-700 ml-1">Message</label>
                <textarea placeholder="Comment pouvons-nous vous aider ?" rows={4} className="w-full px-5 py-4 rounded-xl border border-gray-200 focus:border-[#6852d6] focus:ring-2 focus:ring-[#6852d6]/20 outline-none transition-all font-sora resize-none" />
              </div>

              <Button className="w-full bg-[#6852d6] hover:bg-[#5841c5] text-white rounded-xl py-4 h-auto text-lg font-bold shadow-xl shadow-[#6852d6]/20 transition-all active:scale-95">
                Envoyer le message ✨
              </Button>
            </form>
          </div>
        </div>
      </div>
    </section>
  );
};