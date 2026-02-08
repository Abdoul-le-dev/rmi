import React, { useRef } from "react";
import gsap from "gsap";
import { useGSAP } from "@gsap/react";
import { Button } from "../../../components/ui/button";
import { Award, Users } from "lucide-react";
import { useNavigate } from "react-router-dom";

export const InitiationHeroSection = (): JSX.Element => {
  const container = useRef<HTMLDivElement>(null);
  const navigate = useNavigate()

  useGSAP(() => {
    const tl = gsap.timeline({ defaults: { ease: "power4.out" } });

    // 1. Entrée du texte à gauche
    tl.from(".init-content > *", {
      x: -50,
      opacity: 0,
      duration: 1,
      stagger: 0.2,
    });

    // 2. Animation des images (effet de profondeur)
    tl.from(".main-img", { 
      scale: 0.8, 
      opacity: 0, 
      duration: 1.2 
    }, "-=1")
    .from(".floating-img", { 
      y: 40, 
      opacity: 0, 
      duration: 1 
    }, "-=0.8")

    // 3. Animation des petits badges (Bitcoin / Ethereum) avec un petit rebond
    .from(".crypto-badge", {
      scale: 0,
      opacity: 0,
      duration: 0.8,
      stagger: 0.3,
      ease: "back.out(1.7)",
    }, "-=0.5");

    // 4. Animation de flottement continu pour les badges (Loop)
    gsap.to(".crypto-badge", {
      y: 10,
      duration: 2,
      repeat: -1,
      yoyo: true,
      ease: "sine.inOut",
      stagger: 0.5
    });

  }, { scope: container });

  return (
    <section ref={container} className="flex flex-col items-center px-4 md:px-6 lg:px-8 py-12 md:py-16 lg:py-20 w-full overflow-hidden">
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12 max-w-2xl lg:max-w-6xl items-center">
        
        {/* Colonne Texte */}
        <div className="init-content flex flex-col lg:items-start items-center gap-6 order-2 lg:order-1 w-full">
          <h1 className="text-2xl md:text-4xl font-bold">
            Initiation <span className="text-[#6852d6]">au Trading</span>
          </h1>

          <p className="text-base md:text-lg [font-family:'Sora',Helvetica] text-app-shark leading-8">
            Une méthode pas-à-pas pour maîtriser les graphiques, gérer vos risques et développer une stratégie rentable dès vos premières semaines.
          </p>

          <div className="flex flex-col sm:flex-row gap-4 md:w-auto w-full">
            <Button  onClick={()=>navigate("/devenir-trader-pro")} className="flex md:w-auto w-full items-center gap-2 bg-[#6852d6] hover:bg-[#5841c5] rounded-lg px-6 py-3 h-auto">
              <Award className="w-5 h-5" />
              Rejoignez maintenant
            </Button>
            <Button  onClick={()=>navigate("/devenir-trader-pro")} variant="outline" className="flex items-center md:w-auto w-full gap-2 rounded-lg px-6 py-3 h-auto border-gray-300">
              <Users className="w-5 h-5" />
              Obtenez l'accès exclusif
            </Button>
          </div>
        </div>

        {/* Colonne Visuelle */}
        <div className="order-1 lg:order-2 flex justify-center">
          <div className="relative flex justify-center items-center text-center w-full max-w-sm">
            <img
              src="/trade-bg.png"
              alt="Trading Background"
              className="main-img w-full"
            />
            <img
              src="/init-img.png"
              alt="Trading Illustration"
              className="floating-img w-3/4 absolute top-0 z-10"
            />
            
            {/* Badge Bitcoin */}
            <div className="crypto-badge absolute top-8 left-0 bg-white rounded-2xl p-4 shadow-lg border border-gray-200 z-20">
              <div className="flex items-center gap-2 font-bold mb-1">
                <img src="/bitcoin.png" alt="Bitcoin" className="w-5 h-5" /> 
                <span>Bitcoin</span>
              </div>
            </div>

            {/* Badge Ethereum */}
            <div className="crypto-badge absolute bottom-8 right-0 bg-white rounded-2xl p-4 shadow-lg border border-gray-200 z-20">
              <div className="flex items-center gap-2 font-bold mb-1">
                <img src="/etherium.svg" alt="Ethereum" className="w-5 h-5" /> 
                <span>Etherium</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};