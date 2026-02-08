import { Header } from "../../components/Header/Header";
import { FooterSection } from "../../components/FooterSection/FooterSection";
import { InitiationHeroSection } from "./sections/InitiationHeroSection";
import { InstructorSection } from "./sections/InstructorSection";
import { ObjectivesSection } from "./sections/ObjectivesSection";
import { ContentSection } from "./sections/ContentSection";
import { ReadySection } from "./sections/ReadySection";
import { useEffect } from "react";
import { animateSections } from "../../lib/gsap";


export const InitiationCoursePage = (): JSX.Element => {
  useEffect(() => {
    animateSections();
  }, []);

  return (
    <div className="w-full pt-20">
      <Header />
      <InitiationHeroSection />
      <InstructorSection />
      <ObjectivesSection />
      <ContentSection />
      <ReadySection />
      <FooterSection />
    </div>
  );
};
