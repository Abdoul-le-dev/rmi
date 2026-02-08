import { Header } from "../../components/Header/Header";
import { FooterSection } from "../../components/FooterSection/FooterSection";
import { CommunityHeroSection } from "./sections/CommunityHeroSection";
import { CommunityBenefitsSection } from "./sections/CommunityBenefitsSection";
import { CommunityHowSection } from "./sections/CommunityHowSection";
import { CommunityTestimonialsSection } from "./sections/CommunityTestimonialsSection";
import { CommunityReadySection } from "./sections/CommunityReadySection";
import { useEffect } from "react";
import { animateSections } from "../../lib/gsap";


export const CommunityPage = (): JSX.Element => {
  useEffect(() => {
    animateSections();
  }, []);
  return (
    <div className="w-full pt-20">
      <Header />
      <CommunityHeroSection />
      <CommunityBenefitsSection />
      <CommunityHowSection />
      <CommunityTestimonialsSection />
      <CommunityReadySection />
      <FooterSection />
    </div>
  );
};
