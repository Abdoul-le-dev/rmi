import { Header } from "../../components/Header/Header";
import { FooterSection } from "../../components/FooterSection/FooterSection";
import { FAQHeroSection } from "./sections/FAQHeroSection";
import { FAQAccordionSection } from "./sections/FAQAccordionSection";
import { FAQNoAnswerSection } from "./sections/FAQNoAnswerSection";
import { useEffect } from "react";
import { animateSections } from "../../lib/gsap";


export const FAQPage = (): JSX.Element => {
  useEffect(() => {
    animateSections();
  }, []);
  return (
    <div className="w-full pt-20">
      <Header />
      <FAQHeroSection />
      <FAQAccordionSection />
      <FAQNoAnswerSection />
      <FooterSection />
    </div>
  );
};
