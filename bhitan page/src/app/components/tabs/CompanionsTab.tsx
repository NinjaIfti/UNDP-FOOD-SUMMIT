import { Users, ExternalLink, AlertCircle, Info } from 'lucide-react';

export function CompanionsTab() {
  return (
    <div className="max-w-4xl mx-auto space-y-6">
      <h2 className="text-3xl font-bold text-gray-900">Travel With Companions</h2>

      <div className="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border-2 border-blue-300 p-6">
        <div className="flex gap-4">
          <Users className="w-8 h-8 text-blue-600 flex-shrink-0 mt-1" />
          <p className="text-lg text-gray-800">
            Planning to bring family or friends? Here's what you need to know about traveling to Bhutan with companions.
          </p>
        </div>
      </div>

      {/* Key Requirements */}
      <div className="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        <h3 className="text-xl font-semibold text-gray-900 mb-4">
          Key Requirements for Companions
        </h3>
        <div className="space-y-4">
          <div className="flex items-start gap-4 pb-4 border-b border-gray-100">
            <div className="p-2 bg-red-100 rounded-lg flex-shrink-0">
              <AlertCircle className="w-5 h-5 text-red-600" />
            </div>
            <div>
              <h4 className="font-semibold text-gray-900 mb-2">
                Separate Visa Application
              </h4>
              <p className="text-gray-700">
                Companions must apply for their visa <strong>independently</strong> through the standard tourist visa process. They cannot be included in the Summit visa arrangement.
              </p>
            </div>
          </div>

          <div className="flex items-start gap-4 pb-4 border-b border-gray-100">
            <div className="p-2 bg-amber-100 rounded-lg flex-shrink-0">
              <Info className="w-5 h-5 text-amber-600" />
            </div>
            <div>
              <h4 className="font-semibold text-gray-900 mb-2">
                Tourism Regulations Apply
              </h4>
              <p className="text-gray-700">
                Companions traveling as tourists must follow Bhutan's standard tourism regulations, including the Sustainable Development Fee (SDF).
              </p>
            </div>
          </div>

          <div className="flex items-start gap-4">
            <div className="p-2 bg-purple-100 rounded-lg flex-shrink-0">
              <Users className="w-5 h-5 text-purple-600" />
            </div>
            <div>
              <h4 className="font-semibold text-gray-900 mb-2">
                Licensed Guide Required
              </h4>
              <p className="text-gray-700">
                Companions must be accompanied by a licensed Bhutanese tour guide and travel through a registered tour operator.
              </p>
            </div>
          </div>
        </div>
      </div>

      {/* Costs Breakdown */}
      <div className="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        <h3 className="text-xl font-semibold text-gray-900 mb-4">
          Expected Costs for Companions
        </h3>
        <div className="space-y-3">
          <div className="flex justify-between items-center py-3 border-b border-gray-100">
            <span className="text-gray-700">Visa Fee</span>
            <span className="font-semibold text-gray-900">USD 65</span>
          </div>
          <div className="flex justify-between items-center py-3 border-b border-gray-100">
            <span className="text-gray-700">Sustainable Development Fee (SDF)</span>
            <span className="font-semibold text-gray-900">USD 100 / night</span>
          </div>
          <div className="flex justify-between items-center py-3 border-b border-gray-100">
            <span className="text-gray-700">Tour Package (approx.)</span>
            <span className="font-semibold text-gray-900">USD 250-400 / day</span>
          </div>
          <div className="bg-blue-50 rounded-lg p-4 mt-4">
            <p className="text-sm text-gray-700">
              <strong>Note:</strong> Tour packages typically include accommodation, meals, guide, and transportation. Prices vary by season and hotel category.
            </p>
          </div>
        </div>
      </div>

      {/* Special Considerations */}
      <div className="bg-amber-50 border border-amber-200 rounded-xl p-6">
        <h3 className="text-xl font-semibold text-gray-900 mb-4">
          Special Considerations
        </h3>
        <ul className="space-y-3 text-gray-700">
          <li className="flex items-start gap-3">
            <span className="text-amber-600 mt-1">•</span>
            <span>
              <strong>Children under 12:</strong> May be exempt from or receive reduced SDF rates
            </span>
          </li>
          <li className="flex items-start gap-3">
            <span className="text-amber-600 mt-1">•</span>
            <span>
              <strong>Travel timing:</strong> Companions can arrive with you or independently
            </span>
          </li>
          <li className="flex items-start gap-3">
            <span className="text-amber-600 mt-1">•</span>
            <span>
              <strong>Accommodation:</strong> Can stay at same hotel, subject to availability
            </span>
          </li>
          <li className="flex items-start gap-3">
            <span className="text-amber-600 mt-1">•</span>
            <span>
              <strong>Summit access:</strong> Companions may not be able to attend Summit sessions unless registered
            </span>
          </li>
        </ul>
      </div>

      {/* Planning Tips */}
      <div className="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        <h3 className="text-xl font-semibold text-gray-900 mb-4">
          Planning Tips
        </h3>
        <div className="space-y-3 text-gray-700">
          <div className="flex items-start gap-3">
            <span className="text-[#B91C1C] font-bold">1</span>
            <p>
              Contact a licensed Bhutanese tour operator at least <strong>6-8 weeks before travel</strong>
            </p>
          </div>
          <div className="flex items-start gap-3">
            <span className="text-[#B91C1C] font-bold">2</span>
            <p>
              Coordinate visa timings so companion visas align with Summit dates
            </p>
          </div>
          <div className="flex items-start gap-3">
            <span className="text-[#B91C1C] font-bold">3</span>
            <p>
              Book the same hotel for convenience, mentioning you're with the Summit
            </p>
          </div>
          <div className="flex items-start gap-3">
            <span className="text-[#B91C1C] font-bold">4</span>
            <p>
              Consider arranging activities for companions during Summit sessions
            </p>
          </div>
        </div>
      </div>

      {/* Resources */}
      <div className="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        <h3 className="text-xl font-semibold text-gray-900 mb-4">
          Helpful Resources
        </h3>
        <div className="space-y-3">
          <a
            href="https://www.tourism.gov.bt"
            target="_blank"
            rel="noopener noreferrer"
            className="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors"
          >
            <div>
              <p className="font-medium text-gray-900">Bhutan Tourism Council</p>
              <p className="text-sm text-gray-600">Official tourism information</p>
            </div>
            <ExternalLink className="w-5 h-5 text-gray-400" />
          </a>
          <a
            href="https://www.immigration.gov.bt"
            target="_blank"
            rel="noopener noreferrer"
            className="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors"
          >
            <div>
              <p className="font-medium text-gray-900">Immigration Portal</p>
              <p className="text-sm text-gray-600">Visa application and guidelines</p>
            </div>
            <ExternalLink className="w-5 h-5 text-gray-400" />
          </a>
        </div>
      </div>
    </div>
  );
}
