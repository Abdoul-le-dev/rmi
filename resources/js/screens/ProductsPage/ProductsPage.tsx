import { Header } from "../../components/Header/Header";
import { FooterSection } from "../../components/FooterSection/FooterSection";
import { ProductsHeroSection } from "./sections/ProductsHeroSection";
import { ProductsGridSection } from "./sections/ProductsGridSection";
import { useEffect } from "react";
import { animateSections } from "../../lib/gsap";

export const ProductsPage = (): JSX.Element => {
  useEffect(() => {
    animateSections();
  }, []);
  return (
    <div className="w-full pt-20">
      <Header />
      <ProductsHeroSection />
      <ProductsGridSection />
      <FooterSection />
    </div>
  );
};
