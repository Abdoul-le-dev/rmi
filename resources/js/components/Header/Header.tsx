import { useState, useEffect, useRef } from "react";
import { Link } from "react-router-dom";
import { ChevronDownIcon, MenuIcon, X } from "lucide-react";
import { Button } from "../ui/button";
import gsap from "gsap";

const navigationItems = [
  { label: "Accueil", href: "/", hasDropdown: false },
  {
    label: "Formations",
    href: "#",
    hasDropdown: true,
    dropdown: [
      { label: "Initiation au Trading", href: "/initiation-au-trading" },
      { label: "Cours Complet", href: "/devenir-trader-pro" },
    ],
  },
  { label: "A propos", href: "/about", hasDropdown: false },
  { label: "Communauté", href: "/communaute", hasDropdown: false },
  { label: "Boutique", href: "/produits", hasDropdown: false },
  { label: "Instructeurs", href: "/instructeurs", hasDropdown: false },
  //{ label: "Partner Program", href: "#", hasDropdown: false },
  { label: "FAQ", href: "/faq", hasDropdown: false },
];

export const Header = (): JSX.Element => {
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const [openDropdown, setOpenDropdown] = useState<string | null>(null);
  const headerRef = useRef<HTMLDivElement>(null);
  const navRef = useRef<HTMLDivElement>(null);


  const toggleMenu = () => {
    setIsMenuOpen(!isMenuOpen);
    if (!isMenuOpen) {
      gsap.to(navRef.current, {
        height: "auto",
        duration: 0.3,
        ease: "power2.out",
      });
    }
  };

  return (
    <header
      ref={headerRef}
      className="fixed w-full z-50 top-0 bg-[#FAFAFA] border-b border-gray-200"
    >
      <div className="px-4 md:px-8 lg:px-12 py-5 flex flex-col items-center w-full">
        <div className="flex items-center justify-between w-full max-w-[1400px] gap-4 lg:gap-8">
          <Link to="/" className="flex-shrink-0 hover:opacity-80 transition-opacity">
            <img
              className="w-[120px] md:w-[140px] h-auto object-cover"
              alt="Logo"
              src="/5-17-1.png"
            />
          </Link>

          {/* Desktop Navigation */}
          <nav className="hidden xl:flex items-center gap-1 flex-1 justify-center">
            {navigationItems.map((item, index) => (
              <div key={index} className="relative group">
                {item.hasDropdown ? (
                  <button
                    className="flex items-center gap-1 px-3 py-2 cursor-pointer hover:opacity-80 transition-all duration-300 group"
                    onMouseEnter={() => setOpenDropdown(item.label)}
                    onMouseLeave={() => setOpenDropdown(null)}
                  >
                    <span className="font-medium text-app-shark text-md whitespace-nowrap">
                      {item.label}
                    </span>
                    <ChevronDownIcon className="w-4 h-4 transition-transform group-hover:rotate-180" />
                  </button>
                ) : item.label === "Accueil" ? (
                  <Link
                    to={item.href}
                    className="flex items-center px-3 py-2 hover:opacity-80 transition-opacity"
                  >
                    <span className="font-medium text-app-shark text-md whitespace-nowrap">
                      {item.label}
                    </span>
                  </Link>
                ) : (
                  <a
                    href={item.href}
                    className="flex items-center px-3 py-2 hover:opacity-80 transition-opacity"
                  >
                    <span className="font-medium text-app-shark text-md whitespace-nowrap">
                      {item.label}
                    </span>
                  </a>
                )}

                {/* Dropdown Menu */}
                {item.hasDropdown && item.dropdown && (
                  <div
                    className={`absolute left-0 mt-0 w-48 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden transition-all duration-300 transform origin-top ${
                      openDropdown === item.label
                        ? "opacity-100 scale-y-100"
                        : "opacity-0 scale-y-95 pointer-events-none"
                    }`}
                    onMouseEnter={() => setOpenDropdown(item.label)}
                    onMouseLeave={() => setOpenDropdown(null)}
                  >
                    {item.dropdown.map((subItem, subIndex) => (
                      <Link
                        key={subIndex}
                        to={subItem.href}
                        className="block px-4 py-3 text-md text-app-shark hover:bg-gray-100 hover:text-[#6852d6] transition-colors duration-200 first:rounded-t-lg last:rounded-b-lg"
                      >
                        {subItem.label}
                      </Link>
                    ))}
                  </div>
                )}
              </div>
            ))}
          </nav>

          {/* Desktop Auth Buttons */}
          <div className="hidden xl:flex items-center gap-2 md:gap-3 flex-shrink-0">
            <Link to="/login">
              <Button
                variant="ghost"
                className="h-10 px-2 md:px-3 py-0 rounded-[10px] [font-family:'Sora',Helvetica] font-semibold text-app-shark text-md md:text-md hover:bg-transparent hover:opacity-80"
              >
                Connexion
              </Button>
            </Link>

            <Link to="/register">
              <Button className="h-10 px-2 md:px-3 py-0 rounded-[10px] bg-[linear-gradient(90deg,rgba(104,82,214,1)_0%)] [font-family:'Sora',Helvetica] font-semibold text-white text-md md:text-md hover:opacity-90 whitespace-nowrap">
                S&apos;inscrire
              </Button>
            </Link>
          </div>

          {/* Mobile Menu Button */}
          <button
            onClick={toggleMenu}
            className="xl:hidden flex-shrink-0 p-2 hover:bg-gray-100 rounded-lg transition-colors"
            aria-label="Toggle menu"
          >
            {isMenuOpen ? (
              <X className="w-6 h-6 text-app-shark" />
            ) : (
              <MenuIcon className="w-6 h-6 text-app-shark" />
            )}
          </button>
        </div>

        {/* Mobile Navigation */}
        {isMenuOpen && (
          <nav
            ref={navRef}
            className="xl:hidden w-full mt-4 pt-4 border-t border-gray-200"
          >
            <div className="flex flex-col gap-2">
              {navigationItems.map((item, index) => (
                <div key={index}>
                  {item.hasDropdown ? (
                    <>
                      <button
                        onClick={() =>
                          setOpenDropdown(
                            openDropdown === item.label ? null : item.label
                          )
                        }
                        className="w-full flex items-center justify-between px-3 py-2 text-app-shark hover:bg-gray-100 rounded-lg transition-colors"
                      >
                        <span className="text-md font-medium">{item.label}</span>
                        <ChevronDownIcon
                          className={`w-4 h-4 transition-transform ${
                            openDropdown === item.label ? "rotate-180" : ""
                          }`}
                        />
                      </button>
                      {openDropdown === item.label && item.dropdown && (
                        <div className="pl-4 mt-2 space-y-2">
                          {item.dropdown.map((subItem, subIndex) => (
                            <Link
                              key={subIndex}
                              to={subItem.href}
                              className="block px-3 py-2 text-md text-app-shark hover:bg-gray-100 rounded-lg transition-colors"
                              onClick={() => setIsMenuOpen(false)}
                            >
                              {subItem.label}
                            </Link>
                          ))}
                        </div>
                      )}
                    </>
                  ) : item.label === "Accueil" ? (
                    <Link
                      to={item.href}
                      className="block px-3 py-2 text-md text-app-shark hover:bg-gray-100 rounded-lg transition-colors"
                      onClick={() => setIsMenuOpen(false)}
                    >
                      {item.label}
                    </Link>
                  ) : (
                    <a
                      href={item.href}
                      className="block px-3 py-2 text-md text-app-shark hover:bg-gray-100 rounded-lg transition-colors"
                      onClick={() => setIsMenuOpen(false)}
                    >
                      {item.label}
                    </a>
                  )}
                </div>
              ))}

              <div className="flex gap-2 mt-4 pt-4 border-t border-gray-200">
                <Link to="/login" className="flex-1">
                  <Button
                    variant="outline"
                    className="w-full h-10 rounded-lg border-gray-300"
                    onClick={() => setIsMenuOpen(false)}
                  >
                    Connexion
                  </Button>
                </Link>
                <Link to="/register" className="flex-1">
                  <Button
                    className="w-full h-10 rounded-lg bg-[#6852d6] hover:bg-[#5841c5] text-white"
                    onClick={() => setIsMenuOpen(false)}
                  >
                    S&apos;inscrire
                  </Button>
                </Link>
              </div>
            </div>
          </nav>
        )}
      </div>
    </header>
  );
};
