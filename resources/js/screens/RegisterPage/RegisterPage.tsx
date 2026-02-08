import { Header } from "../../components/Header/Header";
import { FooterSection } from "../../components/FooterSection/FooterSection";
import { RegisterFormSection } from "./sections/RegisterFormSection";
import { useEffect } from "react";
import { animateSections } from "../../lib/gsap";


export const RegisterPage = (): JSX.Element => {
  useEffect(() => {
    animateSections();
  }, []);
  return (
    <div className="w-full pt-20 min-h-screen flex flex-col">
      <Header />
      <div className="flex-grow">
        <RegisterFormSection />
      </div>
      <FooterSection />
    </div>
  );
};
