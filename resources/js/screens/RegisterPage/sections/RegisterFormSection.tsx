import { useEffect, useRef, useState } from "react";
import { Link } from "react-router-dom";
import { Button } from "../../../components/ui/button";
import { Eye, EyeOff } from "lucide-react";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import ReCAPTCHA from "react-google-recaptcha";

gsap.registerPlugin(ScrollTrigger);

export const RegisterFormSection = (): JSX.Element => {
  const [showPassword, setShowPassword] = useState(false);
  const [showConfirmPassword, setShowConfirmPassword] = useState(false);
  const [acceptTerms, setAcceptTerms] = useState(false);
  const [acceptEmails, setAcceptEmails] = useState(false);
  const [captchaValue, setCaptchaValue] = useState<string | null>(null);

  const sectionRef = useRef<HTMLDivElement>(null);

  // GSAP animation on scroll
  useEffect(() => {
    if (!sectionRef.current) return;

    const ctx = gsap.context(() => {
      gsap.from(".gsap-section", {
        y: 50,
        opacity: 0,
        duration: 1,
        stagger: 0.2,
        scrollTrigger: {
          trigger: sectionRef.current,
          start: "top 80%",
        },
      });
    }, sectionRef);

    return () => ctx.revert();
  }, []);

  const isFormValid = acceptTerms && acceptEmails && captchaValue;

  return (
    <section
      ref={sectionRef}
      className="gsap-section flex items-center px-4 md:px-6 lg:px-8 py-12 md:py-16 lg:py-20 w-full min-h-[calc(100vh-200px)]"
    >
      <div className="max-w-7xl w-full mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
        {/* Image Section */}
        <div className="hidden lg:flex justify-center order-2 lg:order-2 hover:scale-105 transition-transform duration-300 gsap-section">
          <div className="relative w-full h-[500px] flex justify-center items-center text-center">
            <img
              src="/Bitcoin-cuate 1.png"
              alt="Register"
              className="w-full rounded-2xl object-cover"
            />
          </div>
        </div>

        {/* Form Section */}
        <div className="flex flex-col gap-6 lg:max-w-md order-1 lg:order-1 gsap-section">
          <div>
            <h1 className="text-2xl md:text-4xl font-bold [font-family:'Archivo',Helvetica] mb-2">
              <span className="text-[#6852d6]">Créer un compte</span>
            </h1>
            <p className="text-sm md:text-base [font-family:'Sora',Helvetica] text-app-shark">
              Rejoignez RMI Class et commencez votre parcours de trader.
            </p>
          </div>

          <form className="flex flex-col gap-4">
            {/* Prénom / Nom */}
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label className="block text-xs md:text-sm font-semibold [font-family:'Sora',Helvetica] text-app-shark mb-2">
                  Prénom
                </label>
                <input
                  type="text"
                  placeholder="Jean"
                  className="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#6852d6] [font-family:'Sora',Helvetica] text-sm transition-all"
                />
              </div>
              <div>
                <label className="block text-xs md:text-sm font-semibold [font-family:'Sora',Helvetica] text-app-shark mb-2">
                  Nom
                </label>
                <input
                  type="text"
                  placeholder="Dupont"
                  className="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#6852d6] [font-family:'Sora',Helvetica] text-sm transition-all"
                />
              </div>
            </div>

            {/* Email */}
            <div>
              <label className="block text-xs md:text-sm font-semibold [font-family:'Sora',Helvetica] text-app-shark mb-2">
                Email
              </label>
              <input
                type="email"
                placeholder="votre@email.com"
                className="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#6852d6] [font-family:'Sora',Helvetica] text-sm transition-all"
              />
            </div>

            {/* Mot de passe */}
            <div>
              <label className="block text-xs md:text-sm font-semibold [font-family:'Sora',Helvetica] text-app-shark mb-2">
                Mot de passe
              </label>
              <div className="relative">
                <input
                  type={showPassword ? "text" : "password"}
                  placeholder="••••••••"
                  className="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#6852d6] [font-family:'Sora',Helvetica] text-sm transition-all"
                />
                <button
                  type="button"
                  onClick={() => setShowPassword(!showPassword)}
                  className="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                >
                  {showPassword ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
                </button>
              </div>
            </div>

            {/* Confirmer le mot de passe */}
            <div>
              <label className="block text-xs md:text-sm font-semibold [font-family:'Sora',Helvetica] text-app-shark mb-2">
                Confirmer le mot de passe
              </label>
              <div className="relative">
                <input
                  type={showConfirmPassword ? "text" : "password"}
                  placeholder="••••••••"
                  className="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#6852d6] [font-family:'Sora',Helvetica] text-sm transition-all"
                />
                <button
                  type="button"
                  onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                  className="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                >
                  {showConfirmPassword ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
                </button>
              </div>
            </div>

            {/* WhatsApp */}
            <div>
              <label className="block text-xs md:text-sm font-semibold [font-family:'Sora',Helvetica] text-app-shark mb-2">
                WhatsApp
              </label>
              <input
                type="tel"
                placeholder="+229 90 00 00 00"
                className="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#6852d6] [font-family:'Sora',Helvetica] text-sm transition-all"
              />
            </div>

            {/* Ville / Fuseau horaire */}
            <div>
              <label className="block text-xs md:text-sm font-semibold [font-family:'Sora',Helvetica] text-app-shark mb-2">
                Ville / Fuseau horaire
              </label>
              <input
                type="text"
                placeholder="Cotonou, Bénin"
                className="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#6852d6] [font-family:'Sora',Helvetica] text-sm transition-all"
              />
            </div>

            {/* Code de parrainage */}
            <div>
              <label className="block text-xs md:text-sm font-semibold [font-family:'Sora',Helvetica] text-app-shark mb-2">
                Code de parrainage (optionnel)
              </label>
              <input
                type="text"
                placeholder="CODE123"
                className="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#6852d6] [font-family:'Sora',Helvetica] text-sm transition-all"
              />
            </div>

            {/* Checkbox Conditions */}
            <div className="flex flex-col gap-2">
              <label className="flex gap-2 text-xs md:text-sm [font-family:'Sora',Helvetica] text-app-shark">
                <input
                  type="checkbox"
                  checked={acceptTerms}
                  onChange={(e) => setAcceptTerms(e.target.checked)}
                  className="w-4 h-4 rounded cursor-pointer"
                />
                <span>J'accepte les <Link to="#" className="text-[#6852d6] hover:underline">
                  conditions générales et la politique de confidentialité
                </Link></span>
                
              </label>

              <label className="flex items-center gap-2 text-xs md:text-sm [font-family:'Sora',Helvetica] text-app-shark">
                <input
                  type="checkbox"
                  checked={acceptEmails}
                  onChange={(e) => setAcceptEmails(e.target.checked)}
                  className="w-4 h-4 rounded cursor-pointer"
                />
                J'accepte de recevoir des emails de RMI Class
              </label>
            </div>

            {/* reCAPTCHA 
            <div className="my-4 w-full flex lg:justify-center lg:items-center justify-start items-start">
              <ReCAPTCHA
              className="w-full flex lg:justify-center lg:items-center justify-start items-start"
              sitekey={import.meta.env.VITE_RECAPTCHA_SITE_KEY}
                onChange={(value: any) => setCaptchaValue(value)}
              />
            </div>*/}

            {/* Submit Button */}
            <Button
              type="submit"
              disabled={!isFormValid}
              className="w-full bg-[#6852d6] hover:bg-[#5841c5] rounded-lg py-3 h-auto font-semibold [font-family:'Sora',Helvetica] flex items-center justify-center gap-2 text-sm md:text-base transition-all hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span>Créer un compte</span>
            </Button>
          </form>

          {/* Already have account */}
          <p className="text-center text-xs md:text-sm [font-family:'Sora',Helvetica] text-app-shark">
            Déjà un compte ?{" "}
            <Link to="/login" className="text-[#6852d6] font-semibold hover:underline">
              Se connecter
            </Link>
          </p>
        </div>
      </div>
    </section>
  );
};
