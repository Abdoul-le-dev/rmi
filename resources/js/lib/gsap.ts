import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

export const animateSections = () => {
  const sections = gsap.utils.toArray<HTMLElement>(".gsap-section");

  sections.forEach((section) => {
    gsap.fromTo(
      section,
      {
        opacity: 0,
        y: 40,
      },
      {
        opacity: 1,
        y: 0,
        duration: 0.9,
        ease: "power3.out",
        scrollTrigger: {
          trigger: section,
          start: "top 85%",
          toggleActions: "play none none none",
        },
      }
    );
  });
};
