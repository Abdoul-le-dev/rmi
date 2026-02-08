import { Header } from "../../components/Header/Header";
import { FooterSection } from "../../components/FooterSection/FooterSection";
import { CourseHeroSection } from "./sections/CourseHeroSection";
import { PricingSection } from "./sections/PricingSection";
import { BenefitsSection } from "./sections/BenefitsSection";
import { ConcreteSection } from "./sections/ConcreteSection";
import { useEffect } from "react";
import { animateSections } from "../../lib/gsap";


export const CompleteCoursePage = (): JSX.Element => {
  useEffect(() => {
    animateSections();
  }, []);

  return (
    <div className="w-full pt-20">
      <Header />
      <CourseHeroSection />
      <PricingSection />
      <BenefitsSection />
      <ConcreteSection />
      <FooterSection />
    </div>
  );
};
