import { ChevronDownIcon } from "lucide-react";
import { Link } from "react-router-dom";
import { Button } from "../../../../components/ui/button";

const navigationItems = [
  { label: "Accueil", href: "/light-home-page", hasDropdown: false },
  { label: "Formations", href: "#", hasDropdown: true },
  { label: "A propos", href: "#", hasDropdown: false },
  { label: "Communauté", href: "#", hasDropdown: false },
  { label: "Boutique", href: "#", hasDropdown: false },
  { label: "Instructeurs", href: "#", hasDropdown: false },
  { label: "Partner Program", href: "#", hasDropdown: false },
  { label: "FAQ", href: "#", hasDropdown: false },
];

export const HeaderSection = (): JSX.Element => {
  return (
    <header className="w-full relative">
      <div className="px-4 md:px-8 lg:px-12 py-5 flex flex-col items-center w-full bg-app-athens-gray">
        <div className="flex items-center justify-between w-full max-w-[1400px] gap-4 lg:gap-8">
          <Link to="/light-home-page" className="flex-shrink-0">
            <img
              className="w-[120px] md:w-[140px] lg:w-[167.65px] h-auto object-cover"
              alt="Logo"
              src="/5-17-1.png"
            />
          </Link>

          <nav className="hidden xl:flex items-center gap-1 flex-1 justify-center">
            {navigationItems.map((item, index) => (
              <div key={index}>
                {item.hasDropdown ? (
                  <button className="flex items-center gap-1 px-3 py-2 cursor-pointer hover:opacity-80 transition-opacity">
                    <span className="font-text-styles-semantic-link font-[number:var(--text-styles-semantic-link-font-weight)] text-app-shark text-[length:var(--text-styles-semantic-link-font-size)] tracking-[var(--text-styles-semantic-link-letter-spacing)] leading-[var(--text-styles-semantic-link-line-height)] [font-style:var(--text-styles-semantic-link-font-style)] whitespace-nowrap">
                      {item.label}
                    </span>
                    <ChevronDownIcon className="w-4 h-4" />
                  </button>
                ) : item.label === "Accueil" ? (
                  <Link
                    to={item.href}
                    className="flex items-center px-3 py-2 hover:opacity-80 transition-opacity"
                  >
                    <span className="font-text-styles-semantic-link font-[number:var(--text-styles-semantic-link-font-weight)] text-app-shark text-[length:var(--text-styles-semantic-link-font-size)] tracking-[var(--text-styles-semantic-link-letter-spacing)] leading-[var(--text-styles-semantic-link-line-height)] [font-style:var(--text-styles-semantic-link-font-style)] whitespace-nowrap">
                      {item.label}
                    </span>
                  </Link>
                ) : (
                  <a
                    href={item.href}
                    className="flex items-center px-3 py-2 hover:opacity-80 transition-opacity"
                  >
                    <span className="font-text-styles-semantic-link font-[number:var(--text-styles-semantic-link-font-weight)] text-app-shark text-[length:var(--text-styles-semantic-link-font-size)] tracking-[var(--text-styles-semantic-link-letter-spacing)] leading-[var(--text-styles-semantic-link-line-height)] [font-style:var(--text-styles-semantic-link-font-style)] whitespace-nowrap">
                      {item.label}
                    </span>
                  </a>
                )}
              </div>
            ))}
          </nav>

          <div className="flex items-center gap-2 md:gap-3 flex-shrink-0">
            <Button
              variant="ghost"
              className="h-9 px-2 md:px-3 py-0 rounded-[10px] [font-family:'Sora',Helvetica] font-semibold text-app-shark text-xs md:text-sm hover:bg-transparent hover:opacity-80"
            >
              Connexion
            </Button>

            <Button className="h-9 px-2 md:px-3 py-0 rounded-[10px] bg-[linear-gradient(90deg,rgba(104,82,214,1)_0%)] [font-family:'Sora',Helvetica] font-semibold text-app-athens-gray text-xs md:text-sm hover:opacity-90 whitespace-nowrap">
              S&#39;inscrire
            </Button>
          </div>
        </div>
      </div>
    </header>
  );
};
