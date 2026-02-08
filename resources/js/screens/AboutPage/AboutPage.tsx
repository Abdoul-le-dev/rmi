import { Header } from "../../components/Header/Header";
import { FooterSection } from "../../components/FooterSection/FooterSection";
import { AboutHeroSection } from "./sections/AboutHeroSection";
import { WhoIsSection } from "./sections/WhoIsSection";
import { BeforeStartingSection } from "./sections/BeforeStartingSection";
import { ContactSection } from "./sections/ContactSection";
import { useEffect } from "react";
import { animateSections } from "../../lib/gsap";


export const AboutPage = (): JSX.Element => {
  useEffect(() => {
    animateSections();
  }, []);
  return (
    <div className="w-full pt-20">
      <Header />
      <AboutHeroSection />
      <WhoIsSection />
      <BeforeStartingSection />
      <ContactSection />
      <FooterSection />
    </div>
  );
};
