//import { CommunitySection } from "./sections/CommunitySection/CommunitySection";
import { CoursesSection } from "./sections/CoursesSection";
import { FeaturesSection } from "./sections/FeaturesSection/FeaturesSection";
import { FooterSection } from "../../components/FooterSection/FooterSection";
import { HeroSection } from "./sections/HeroSection";
import { TestimonialsSection } from "./sections/TestimonialsSection/TestimonialsSection";
import { Header } from "../../components/Header/Header";

export const LightHomePage = (): JSX.Element => {


  return (
    <div className="w-screen bg-app-athens-gray relative overflow-x-hidden">
      <Header />
      <main className="w-full mt-8">
        <HeroSection />
        <CoursesSection />
        <FeaturesSection />
        <TestimonialsSection />
      </main>
      <FooterSection />
    </div>
  );
};
