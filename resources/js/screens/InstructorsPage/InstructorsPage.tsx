import { Header } from "../../components/Header/Header";
import { FooterSection } from "../../components/FooterSection/FooterSection";
import { InstructorsHeroSection } from "./sections/InstructorsHeroSection";
import { InstructorsGridSection } from "./sections/InstructorsGridSection";
import { useEffect } from "react";
import { animateSections } from "../../lib/gsap";


export const InstructorsPage = (): JSX.Element => {
  useEffect(() => {
    animateSections();
  }, []);

  return (
    <div className="w-full pt-20">
      <Header />
      <InstructorsHeroSection />
      <InstructorsGridSection />
      <FooterSection />
    </div>
  );
};
