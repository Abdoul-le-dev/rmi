import { useEffect, useRef, useState } from "react";
import { Link } from "react-router-dom";
import { Button } from "../../../components/ui/button";
import { Eye, EyeOff } from "lucide-react";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import ReCAPTCHA from "react-google-recaptcha";

gsap.registerPlugin(ScrollTrigger);

export const LoginFormSection = (): JSX.Element => {
  const [showPassword, setShowPassword] = useState(false);
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

  const isFormValid = !!captchaValue; // le bouton sera actif seulement si captcha validé

  return (
    <section
      ref={sectionRef}
      className="flex items-center px-4 md:px-6 lg:px-8 py-12 md:py-16 lg:py-20 w-full min-h-[calc(100vh-200px)]"
    >
      <div className="max-w-7xl w-full mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
        {/* Image Section */}
        <div className="hidden lg:flex justify-center hover:scale-105 transition-transform duration-300 gsap-section">
          <div className="relative w-full max-w-md h-[500px] flex justify-center items-center text-center">
            <img
              src="/Finance-app-cuate.svg"
              alt="Login"
              className="h-full rounded-2xl object-cover"
            />
          </div>
        </div>

        {/* Form Section */}
        <div className="flex flex-col gap-8 lg:max-w-md gsap-section">
          <div>
            <h1 className="text-2xl md:text-4xl font-bold text-[#6852d6] mb-2">
              Connectez-vous
            </h1>
            <p className="text-base md:text-lg [font-family:'Sora',Helvetica] text-app-shark">
              Accédez à votre compte RMI Class
            </p>
          </div>

          <form className="flex flex-col gap-6">
            {/* Email */}
            <div>
              <label className="block text-sm font-semibold [font-family:'Sora',Helvetica] text-app-shark mb-2">
                Email
              </label>
              <input
                type="email"
                placeholder="votre@email.com"
                className="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#6852d6] [font-family:'Sora',Helvetica] transition-all"
              />
            </div>

            {/* Mot de passe */}
            <div>
              <div className="flex justify-between items-center mb-2">
                <label className="block text-sm font-semibold [font-family:'Sora',Helvetica] text-app-shark">
                  Mot de passe
                </label>
                <Link
                  to="#"
                  className="text-sm text-[#6852d6] hover:underline [font-family:'Sora',Helvetica]"
                >
                  Mot de passe oublié ?
                </Link>
              </div>
              <div className="relative">
                <input
                  type={showPassword ? "text" : "password"}
                  placeholder="••••••••"
                  className="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#6852d6] [font-family:'Sora',Helvetica] transition-all"
                />
                <button
                  type="button"
                  onClick={() => setShowPassword(!showPassword)}
                  className="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                >
                  {showPassword ? <EyeOff className="w-5 h-5" /> : <Eye className="w-5 h-5" />}
                </button>
              </div>
            </div>

            {/* reCAPTCHA */}
            <div className="my-4 w-full flex lg:justify-center lg:items-center justify-start items-start">
              <ReCAPTCHA
              className="w-full flex lg:justify-center lg:items-center justify-start items-start"
              sitekey={import.meta.env.VITE_RECAPTCHA_SITE_KEY}
                onChange={(value: any) => setCaptchaValue(value)}
              />
            </div>

            {/* Submit Button */}
            <Button
              type="submit"
              disabled={!isFormValid}
              className="w-full bg-[#6852d6] hover:bg-[#5841c5] rounded-lg py-3 h-auto font-semibold [font-family:'Sora',Helvetica] flex items-center justify-center gap-2 transition-all hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span>Se connecter</span>
            </Button>
          </form>

          {/* Create account link */}
          <p className="text-center text-sm md:text-base [font-family:'Sora',Helvetica] text-app-shark">
            Pas encore de compte ?{" "}
            <Link to="/register" className="text-[#6852d6] font-semibold hover:underline">
              Créer un compte
            </Link>
          </p>
        </div>
      </div>
    </section>
  );
};
