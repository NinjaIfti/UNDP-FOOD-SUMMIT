import { useState, useEffect } from 'react';
import {
  MapPin,
  Plane,
  FileCheck,
  Luggage,
  Building2,
  CalendarPlus,
  Users
} from 'lucide-react';

interface Tab {
  id: string;
  label: string;
  icon: React.ReactNode;
}

const tabs: Tab[] = [
  { id: 'field-visits', label: 'Field Visits', icon: <MapPin className="w-5 h-5" /> },
  { id: 'air-travel', label: 'Air Travel', icon: <Plane className="w-5 h-5" /> },
  { id: 'visa', label: 'Visa & Entry', icon: <FileCheck className="w-5 h-5" /> },
  { id: 'arrival', label: 'Upon Arrival', icon: <Luggage className="w-5 h-5" /> },
  { id: 'accommodation', label: 'Accommodation', icon: <Building2 className="w-5 h-5" /> },
  { id: 'extend', label: 'Extend Stay', icon: <CalendarPlus className="w-5 h-5" /> },
  { id: 'companions', label: 'Travel With Companions', icon: <Users className="w-5 h-5" /> },
];

interface TabNavigationProps {
  activeTab: string;
  onTabChange: (tabId: string) => void;
}

export function TabNavigation({ activeTab, onTabChange }: TabNavigationProps) {
  const [isSticky, setIsSticky] = useState(false);

  useEffect(() => {
    const handleScroll = () => {
      setIsSticky(window.scrollY > 300);
    };

    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  return (
    <div
      className={`w-full bg-white transition-all duration-300 z-40 ${
        isSticky ? 'sticky top-0 shadow-md' : ''
      }`}
    >
      <div className="max-w-7xl mx-auto">
        <div className="overflow-x-auto scrollbar-hide">
          <div className="flex gap-2 px-6 py-4 min-w-max">
            {tabs.map((tab) => (
              <button
                key={tab.id}
                onClick={() => onTabChange(tab.id)}
                className={`flex items-center gap-2 px-4 py-3 rounded-lg whitespace-nowrap transition-all ${
                  activeTab === tab.id
                    ? 'bg-[#B91C1C] text-white shadow-md'
                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                }`}
              >
                {tab.icon}
                <span className="font-medium">{tab.label}</span>
              </button>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}
