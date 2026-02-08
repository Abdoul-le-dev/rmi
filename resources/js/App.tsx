import { RouterProvider, createBrowserRouter } from "react-router-dom";
import { LightHomePage } from "./screens/LightHomePage";
import { AboutPage } from "./screens/AboutPage/AboutPage";
import { CompleteCoursePage } from "./screens/CompleteCoursePage/CompleteCoursePage";
import { InitiationCoursePage } from "./screens/InitiationCoursePage/InitiationCoursePage";
import { InstructorsPage } from "./screens/InstructorsPage/InstructorsPage";
import { ProductsPage } from "./screens/ProductsPage/ProductsPage";
import { CommunityPage } from "./screens/CommunityPage/CommunityPage";
import { FAQPage } from "./screens/FAQPage/FAQPage";
import { LoginPage } from "./screens/LoginPage/LoginPage";
import { RegisterPage } from "./screens/RegisterPage/RegisterPage";
import { LegalNoticePage } from "./screens/LegalNoticePage/LegalNoticePage";
import { PrivacyPolicyPage } from "./screens/PrivacyPolicyPage/PrivacyPolicyPage";
import { TermsAndConditionsPage } from "./screens/TermsAndConditionsPage/TermsAndConditionsPage";

const router = createBrowserRouter([
  {
    path: "/",
    element: <LightHomePage />,
  },
  {
    path: "/light-home-page",
    element: <LightHomePage />,
  },
  {
    path: "/about",
    element: <AboutPage />,
  },
  {
    path: "/devenir-trader-pro",
    element: <CompleteCoursePage />,
  },
  {
    path: "/initiation-au-trading",
    element: <InitiationCoursePage />,
  },
  {
    path: "/instructeurs",
    element: <InstructorsPage />,
  },
  {
    path: "/produits",
    element: <ProductsPage />,
  },
  {
    path: "/communaute",
    element: <CommunityPage />,
  },
  {
    path: "/faq",
    element: <FAQPage />,
  },
  {
    path: "/login",
    element: <LoginPage />,
  },
  {
    path: "/register",
    element: <RegisterPage />,
  },
  {
    path: "/mentions-legales",
    element: <LegalNoticePage />,
  },
  {
    path: "/politique-confidentialite",
    element: <PrivacyPolicyPage />,
  },
  {
    path: "/conditions-generales",
    element: <TermsAndConditionsPage />,
  },
]);

export const App = () => {
  return <RouterProvider router={router} />;
};
