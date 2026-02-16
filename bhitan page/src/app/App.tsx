import { useState } from 'react';
import { TabNavigation } from './components/TabNavigation';
import { FieldVisitsTab } from './components/tabs/FieldVisitsTab';
import { AirTravelTab } from './components/tabs/AirTravelTab';
import { VisaTab } from './components/tabs/VisaTab';
import { ArrivalTab } from './components/tabs/ArrivalTab';
import { AccommodationTab } from './components/tabs/AccommodationTab';
import { ExtendStayTab } from './components/tabs/ExtendStayTab';
import { CompanionsTab } from './components/tabs/CompanionsTab';
import { motion, AnimatePresence } from 'motion/react';
import { Check, X, Ticket, UtensilsCrossed, Plane, Building, Calendar } from 'lucide-react';

export default function App() {
  const [activeTab, setActiveTab] = useState('field-visits');

  const renderTabContent = () => {
    switch (activeTab) {
      case 'field-visits':
        return <FieldVisitsTab />;
      case 'air-travel':
        return <AirTravelTab />;
      case 'visa':
        return <VisaTab />;
      case 'arrival':
        return <ArrivalTab />;
      case 'accommodation':
        return <AccommodationTab />;
      case 'extend':
        return <ExtendStayTab />;
      case 'companions':
        return <CompanionsTab />;
      default:
        return <FieldVisitsTab />;
    }
  };

  return (
    <div className="min-h-screen bg-white">
      {/* Participation Costs Section */}
      <div className="bg-gradient-to-br from-amber-50 to-red-50 py-16 px-6">
        <div className="max-w-6xl mx-auto">
          {/* Header */}
          <div className="text-center mb-12">
            <h2 className="text-4xl font-bold text-gray-900 mb-4">
              WHAT ARE THE PARTICIPATION COSTS?
            </h2>
            <div className="max-w-3xl mx-auto space-y-4">
              <p className="text-lg text-gray-700 leading-relaxed">
                Participation in the Conscious Food Systems Summit requires an entrance ticket, which helps cover the essential costs of organizing and delivering the event, including venue arrangements, meals, local logistics, and administrative coordination.
              </p>
              <div className="bg-amber-100 border-l-4 border-[#B91C1C] p-4 rounded-r-lg">
                <p className="text-gray-800">
                  <strong>Announcement:</strong> The official ticket price will be announced following the formal launch of the Summit in early March. We kindly invite participants to stay tuned for further updates.
                </p>
              </div>
            </div>
          </div>

          {/* Two Column Layout */}
          <div className="grid md:grid-cols-2 gap-8 mb-8">
            {/* What's Included Card */}
            <div className="bg-white rounded-2xl shadow-xl overflow-hidden">
              <div className="bg-gradient-to-r from-[#B91C1C] to-red-700 p-4">
                <div className="flex items-center gap-2">
                  <div className="bg-white/20 p-2 rounded-lg">
                    <Check className="w-6 h-6 text-white" />
                  </div>
                  <h3 className="text-xl font-bold text-white">
                    What's Included
                  </h3>
                </div>
              </div>
              
              <div className="p-6 space-y-5">
                {/* Event Access */}
                <div className="flex gap-4">
                  <div className="flex-shrink-0 mt-1">
                    <div className="bg-green-100 p-2 rounded-lg">
                      <Calendar className="w-5 h-5 text-green-600" />
                    </div>
                  </div>
                  <div>
                    <h4 className="font-semibold text-gray-900 mb-1">Event Access</h4>
                    <p className="text-sm text-gray-600">Entrance and participation from 01–04 September</p>
                  </div>
                </div>

                {/* Meals */}
                <div className="flex gap-4">
                  <div className="flex-shrink-0 mt-1">
                    <div className="bg-orange-100 p-2 rounded-lg">
                      <UtensilsCrossed className="w-5 h-5 text-orange-600" />
                    </div>
                  </div>
                  <div>
                    <h4 className="font-semibold text-gray-900 mb-1">Daily Meals</h4>
                    <ul className="text-sm text-gray-600 space-y-1">
                      <li>• Daily lunch for summit participants</li>
                      <li>• Two tea/coffee breaks per day</li>
                      <li>• Between two to three group dinners</li>
                    </ul>
                  </div>
                </div>

                {/* Visa */}
                <div className="flex gap-4">
                  <div className="flex-shrink-0 mt-1">
                    <div className="bg-blue-100 p-2 rounded-lg">
                      <Ticket className="w-5 h-5 text-blue-600" />
                    </div>
                  </div>
                  <div>
                    <h4 className="font-semibold text-gray-900 mb-1">Visa Application</h4>
                    <p className="text-sm text-gray-600">
                      Visa application fee of BTN 5,000, plus full coordination of the visa entry permission process. Participants arranging their visa independently will pay USD 65 per person. Indian nationals are exempt from visa fees.
                    </p>
                  </div>
                </div>

                {/* SDF Waiver */}
                <div className="flex gap-4 bg-amber-50 p-4 rounded-lg border border-amber-200">
                  <div className="flex-shrink-0 mt-1">
                    <div className="bg-amber-200 p-2 rounded-lg">
                      <Check className="w-5 h-5 text-amber-700" />
                    </div>
                  </div>
                  <div>
                    <h4 className="font-semibold text-gray-900 mb-1">SDF Waiver (Special)</h4>
                    <p className="text-sm text-gray-600">
                      The Royal Government of Bhutan is granting all Summit participants a waiver of the Sustainable Development Fee (SDF), normally USD 100 per adult per night, for the official event period from Friday 28 August to Sunday 06 September, 2026.
                    </p>
                  </div>
                </div>

                {/* Transportation */}
                <div className="flex gap-4">
                  <div className="flex-shrink-0 mt-1">
                    <div className="bg-purple-100 p-2 rounded-lg">
                      <Plane className="w-5 h-5 text-purple-600" />
                    </div>
                  </div>
                  <div>
                    <h4 className="font-semibold text-gray-900 mb-1">Local Transportation</h4>
                    <p className="text-sm text-gray-600">
                      Transportation between the hotel in Paro and the event venue from 01-04 September
                    </p>
                  </div>
                </div>
              </div>
            </div>

            {/* What's NOT Included Card */}
            <div className="bg-white rounded-2xl shadow-xl overflow-hidden">
              <div className="bg-gradient-to-r from-gray-600 to-gray-800 p-4">
                <div className="flex items-center gap-2">
                  <div className="bg-white/20 p-2 rounded-lg">
                    <X className="w-6 h-6 text-white" />
                  </div>
                  <h3 className="text-xl font-bold text-white">
                    What's NOT Included
                  </h3>
                </div>
              </div>
              
              <div className="p-6 space-y-5">
                {/* International Travel */}
                <div className="flex gap-4">
                  <div className="flex-shrink-0 mt-1">
                    <div className="bg-gray-100 p-2 rounded-lg">
                      <Plane className="w-5 h-5 text-gray-500" />
                    </div>
                  </div>
                  <div>
                    <h4 className="font-semibold text-gray-900 mb-1">International Travel</h4>
                    <p className="text-sm text-gray-600">International travel to and from Bhutan (Paro airport)</p>
                  </div>
                </div>

                {/* Accommodation */}
                <div className="flex gap-4">
                  <div className="flex-shrink-0 mt-1">
                    <div className="bg-gray-100 p-2 rounded-lg">
                      <Building className="w-5 h-5 text-gray-500" />
                    </div>
                  </div>
                  <div>
                    <h4 className="font-semibold text-gray-900 mb-1">Accommodation</h4>
                    <p className="text-sm text-gray-600">Hotel accommodation costs in Paro</p>
                  </div>
                </div>

                {/* Airport Transfers */}
                <div className="flex gap-4">
                  <div className="flex-shrink-0 mt-1">
                    <div className="bg-gray-100 p-2 rounded-lg">
                      <X className="w-5 h-5 text-gray-500" />
                    </div>
                  </div>
                  <div>
                    <h4 className="font-semibold text-gray-900 mb-1">Airport Transfers</h4>
                    <p className="text-sm text-gray-600">Transportation between Paro airport and your hotel</p>
                  </div>
                </div>

                {/* Extra Dinners */}
                <div className="flex gap-4">
                  <div className="flex-shrink-0 mt-1">
                    <div className="bg-gray-100 p-2 rounded-lg">
                      <UtensilsCrossed className="w-5 h-5 text-gray-500" />
                    </div>
                  </div>
                  <div>
                    <h4 className="font-semibold text-gray-900 mb-1">Additional Dinners</h4>
                    <p className="text-sm text-gray-600">One to two dinners outside the official program</p>
                  </div>
                </div>

                {/* Personal Expenses */}
                <div className="flex gap-4 bg-gray-50 p-4 rounded-lg border border-gray-200">
                  <div className="flex-shrink-0 mt-1">
                    <div className="bg-gray-200 p-2 rounded-lg">
                      <X className="w-5 h-5 text-gray-600" />
                    </div>
                  </div>
                  <div>
                    <h4 className="font-semibold text-gray-900 mb-1">Personal Expenses</h4>
                    <p className="text-sm text-gray-600">
                      Any personal expenses or additional costs incurred during your stay in Bhutan
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Tab Navigation */}
      <TabNavigation activeTab={activeTab} onTabChange={setActiveTab} />

      {/* Tab Content with Animation */}
      <div className="py-12 px-6">
        <AnimatePresence mode="wait">
          <motion.div
            key={activeTab}
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: -20 }}
            transition={{ duration: 0.3 }}
          >
            {renderTabContent()}
          </motion.div>
        </AnimatePresence>
      </div>

      {/* Mobile Bottom CTA */}
      <div className="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 shadow-lg z-50">
        <button className="w-full py-3 bg-[#B91C1C] text-white font-semibold rounded-lg hover:bg-[#991B1B] transition-colors">
          Start Registration
        </button>
      </div>
    </div>
  );
}