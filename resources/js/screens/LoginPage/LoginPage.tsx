import { Header } from "../../components/Header/Header";
import { FooterSection } from "../../components/FooterSection/FooterSection";
import { LoginFormSection } from "./sections/LoginFormSection";
import { useEffect } from "react";
import { animateSections } from "../../lib/gsap";

export const LoginPage = (): JSX.Element => {
  useEffect(() => {
    animateSections();
  }, []);
  return (
    <div className="w-full pt-20 min-h-screen flex flex-col">
      <Header />
      <div className="flex-grow">
        <LoginFormSection />
      </div>
      <FooterSection />
    </div>
  );
};
